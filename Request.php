<?php

namespace X11;

class Request {

  protected static function debug($data, $request) {
    $bt = debug_backtrace();
    $requestName = $bt[2]['function'];
    echo "\n", str_pad("[ {$requestName} ]", 120, '-', STR_PAD_BOTH), "\n";
    foreach ($data as $field) {
      if ($field[2] == Type::STRING8) {
        echo str_pad('"' . $field[1] . '"', 40, ' ', STR_PAD_LEFT);
        echo str_pad('[' . strlen($field[1]) . ']', 26, ' ', STR_PAD_LEFT);
      } else if ($field[2] == Type::PAD4) {
        echo str_pad('[' . $field[1] . ']', 66, ' ', STR_PAD_LEFT);
      } else {
        echo str_pad($field[1], 40, ' ', STR_PAD_LEFT);
        if (is_int($field[1])) {
          echo str_pad('0x' . dechex($field[1]), 16, ' ', STR_PAD_LEFT);
        } else {
          echo str_pad('', 16);
        }
        echo str_pad(' [' . Type::$size[Type::$format[$field[2]]] . ']', 10, ' ', STR_PAD_LEFT);
      }
      echo " {$field[0]}\n";
    }
    $bytes = unpack('C*', $request);
    echo '| ';
    foreach ($bytes as $i => $byte) {
      $hex = dechex($byte);
      echo '0x', ($byte < 0x10 ? '0' : ''), $hex, ' ';
      if ($i % 4 == 0) {
        echo '| ';
      }
      if ($i % 16 == 0) {
        echo "\n| ";
      }
    }
    echo "\n";
  }

  protected static function doRequest($data) {
    $formatString = '';
    $values = [];
    foreach ($data as $field) {
      $value = $field[1];
      $type = $field[2];
      if ($type == Type::PAD4) {
        if ($value > 0) {
          $formatString .= str_repeat('C', $value);
          $values = array_merge($values, array_fill(0, $value, 0));
        }
      } else {
        if (in_array($type, [Type::ENUM8, Type::ENUM16, Type::ENUM32])) {
          $enum = $field[3];
          $value = array_search($value, $enum);
        }
        $format = Type::$format[$type];
        $formatString .= $format;
        $values[] = $value;
      }
    }
    $request = pack($formatString, ...$values);
    if (DEBUG) {
      self::debug($data, $request);
    }
    Connection::write($request);
  }

  protected static function addBitmaskList($data, $valueMap, $values) {
    $valueMask = 0;
    $valueList = [];
    $length = 0;
    foreach ($valueMap as $i => $map) {
      $name = $map[0];
      if (!isset($values[$name])) {
        continue;
      }
      $type = $map[1];
      $value = $values[$name];
      if (in_array($type, [Type::ENUM8, Type::ENUM16, Type::ENUM32])) {
        $possibleValues = $map[2];
        $valueList[] = [$name, $value, $type, $possibleValues];
      } else {
        $valueList[] = [$name, $value, $type];
      }
      $length += Type::$size[Type::$format[$type]];
      $valueMask |= pow(2, $i);
    }
    $data[2][1] += $length >> 2;
    $data[] = ['valueMask', $valueMask, Type::CARD32];
    return array_merge($data, $valueList);
  }

  public static function ConnectionInit($byteOrder, $authorizationProtocolName = '', $authorizationProtocolData = '') {
    $apnlen = strlen($authorizationProtocolName);
    $apnpad = Connection::pad4($apnlen);
    $apdlen = strlen($authorizationProtocolData);
    $apdpad = Connection::pad4($apdlen);
    self::doRequest([
      ['byteOrder', $byteOrder, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['protocolMajorVersion', 11, Type::CARD16],
      ['protocolMinorVersion', 0, Type::CARD16],
      ['lengthOfAuthorizationProtocolName', ($apnlen + $apnpad) >> 2, Type::CARD16],
      ['lengthOfAuthorizationProtocolData', ($apdlen + $apdpad) >> 2, Type::CARD16],
      ['unused', 0, Type::CARD16],
      ['AuthorizationProtocolName', $authorizationProtocolName, Type::STRING8],
      ['pad', $apnpad, Type::PAD4],
      ['AuthorizationProtocolData', $authorizationProtocolData, Type::STRING8],
      ['pad', $apdpad, Type::PAD4]
    ]);
    return Response::ConnectionInit();
  }

  public static function CreateWindow(
    $depth, $wid, $parent, $x,
    $y, $width, $height, $borderWidth,
    $class, $visual, $values
  ) {
    $data = [
      ['opcode', 1, Type::BYTE],
      ['depth', $depth, Type::CARD8],
      ['requestLength', 8, Type::CARD16],
      ['wid', $wid, Type::WINDOW],
      ['parent', $parent, Type::WINDOW],
      ['x', $x, Type::INT16],
      ['y', $y, Type::INT16],
      ['width', $width, Type::CARD16],
      ['height', $height, Type::CARD16],
      ['borderWidth', $borderWidth, Type::CARD16],
      ['class', $class, Type::ENUM16, ['CopyFromParent', 'InputOutput', 'InputOnly']],
      ['visual', $visual, Type::VISUALID]
    ];
    $valueMap = [
      ['backgroundPixmap', Type::PIXMAP],
      ['backgroundPixel', Type::CARD32],
      ['borderPixmap', Type::PIXMAP],
      ['borderPixel', Type::CARD32],
      ['bitGravity', Type::BYTE],
      ['winGravity', Type::BYTE],
      ['backingStore', Type::ENUM8, ['NotUseful', 'WhenMapped', 'Always']],
      ['backingPlanes', Type::CARD32],
      ['backingPixel', Type::CARD32],
      ['overrideRedirect', Type::BYTE],
      ['saveUnder', Type::BYTE],
      ['eventMask', Type::CARD32],
      ['doNotPropagateMask', Type::CARD32],
      ['colormap', Type::CARD32],
      ['cursor', Type::CARD32]
    ];
    $data = self::addBitmaskList($data, $valueMap, $values);
    self::doRequest($data);
  }

  public static function ChangeWindowAttributes($window, $values) {
    $data = [
      ['opcode', 2, Type::BYTE],
      ['unuset', 0, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['window', $window, Type::WINDOW]
    ];
    $valueMap = [
      ['backgroundPixmap', Type::PIXMAP],
      ['backgroundPixel', Type::CARD32],
      ['borderPixmap', Type::PIXMAP],
      ['borderPixel', Type::CARD32],
      ['bitGravity', Type::BYTE],
      ['winGravity', Type::BYTE],
      ['backingStore', Type::ENUM8, ['NotUseful', 'WhenMapped', 'Always']],
      ['backingPlanes', Type::CARD32],
      ['backingPixel', Type::CARD32],
      ['overrideRedirect', Type::BYTE],
      ['saveUnder', Type::BYTE],
      ['eventMask', Type::CARD32],
      ['doNotPropagateMask', Type::CARD32],
      ['colormap', Type::CARD32],
      ['cursor', Type::CARD32]
    ];
    $data = self::addBitmaskList($data, $valueMap, $values);
    self::doRequest($data);
  }

  public static function GetWindowAttributes($window) {
    self::doRequest([
      ['opcode', 3, Type::BYTE],
      ['unuset', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['window', $window, Type::WINDOW]
    ]);
    return Response::GetWindowAttributes();
  }

  public static function DestroyWindow($window) {
    self::doRequest([
      ['opcode', 4, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['window', $window, Type::WINDOW]
    ]);
  }

  public static function DestroySubWindows($window) {
    self::doRequest([
      ['opcode', 5, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['window', $window, Type::WINDOW]
    ]);
  }

  public static function ChangeSaveSet($mode, $window) {
    self::doRequest([
      ['opcode', 6, Type::BYTE],
      ['mode', $mode, Type::ENUM8, ['Insert', 'Delete']],
      ['requestLength', 2, Type::CARD16],
      ['window', $window, Type::WINDOW]
    ]);
  }

  public static function ReparentWindow($window, $parent, $x, $y) {
    self::doRequest([
      ['opcode', 7, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4, Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['parent', $parent, Type::WINDOW],
      ['x', $x, Type::INT16],
      ['y', $y, Type::INT16],
    ]);
  }

  public static function MapWindow($window) {
    self::doRequest([
      ['opcode', 8, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['window', $window, Type::WINDOW]
    ]);
  }

  public static function MapSubwindows($window) {
    self::doRequest([
      ['opcode', 9, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['window', $window, Type::WINDOW]
    ]);
  }

  public static function UnmapWindow($window) {
    self::doRequest([
      ['opcode', 10, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['window', $window, Type::WINDOW]
    ]);
  }

  public static function UnmapSubwindows($window) {
    self::doRequest([
      ['opcode', 11, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['window', $window, Type::WINDOW]
    ]);
  }

  public static function ConfigureWindow($window, $values) {
    $data = [
      ['opcode', 12, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['window', $window, Type::WINDOW]
    ];
    $valueMap = [
      'x' => Type::INT16,
      'y' => Type::INT16,
      'width' => Type::CARD16,
      'height' => Type::CARD16,
      'borderWidth' => Type::CARD16,
      'sibling' => Type::WINDOW,
      ['stackMode', Type::ENUM8, ['Above', 'Below', 'TopIf', 'BottomIf', 'Opposite']]
    ];
    $data = self::addBitmaskList($data, $valueMap, $values);
    self::doRequest($data);
  }

  public static function CirculateWindow($direction, $window) {
    self::doRequest([
      ['opcode', 13, Type::BYTE],
      ['direction', 0, Type::ENUM8, ['RaiseLowest', 'LowerHeighest']],
      ['requestLength', 2, Type::CARD16],
      ['window', $window, Type::WINDOW]
    ]);
  }

  public static function GetGeometry($drawable) {
    self::doRequest([
      ['opcode', 14, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE]
    ]);
    return Response::GetGeometry();
  }

  public static function QueryTree($window) {
    self::doRequest([
      ['opcode', 15, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['window', $window, Type::WINDOW]
    ]);
    return Response::QueryTree();
  }

  public static function InternAtom($name) {
    $length = strlen($name);
    self::doRequest([
      ['opcode', 16, Type::BYTE],
      ['onlyIfExists', 0, Type::BOOL],
      ['requestLength', 2, Type::CARD16],
      ['n', $length, Type::CARD16],
      ['unused', 0, Type::CARD16],
      ['name', $name, Type::STRING8],
      ['pad', pad4($length), Type::PAD4]
    ]);
    return Respnose::InternAtom();
  }

  public static function GetAtomName($atom) {
    self::doRequest([
      ['opcode', 17, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['atom', $atom, Type::ATOM],
    ]);
    return Response::GetAtomName();
  }

  public static function ChangeProperty($mode, $window, $property, $type, $format, $data) {
    $length = strlen($data);
    if ($format == 16) {
      $lengthInFormatUnit = $length >> 1;
    } else if ($format == 32) {
      $lengthInFormatUnit = $length >> 2;
    } else {
      $format = 8;
      $lengthInFormatUnit = $length;
    }
    $pad = Connection::pad4($length);
    self::doRequest([
      ['opcode', 18, Type::BYTE],
      ['mode', $mode, Type::ENUM8, ['Replace', 'Prepend', 'Append']],
      ['requestLength', 6 + (($length + $pad) >> 2), Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['property', $property, Type::ATOM],
      ['type', $type, Type::ATOM],
      ['format', $format, Type::CARD8],
      ['unused', 0, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['dataLength', $lengthInFormatUnit, Type::CARD32],
      ['data', $data, Type::STRING8],
      ['pad', $pad, Type::PAD4]
    ]);
  }

  public static function DeleteProperty($window, $property) {
    self::doRequest([
      ['opcode', 19, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['property', $property, Type::ATOM]
    ]);
  }

  public static function GetProperty($delete, $window, $property, $type, $longOffset, $longLength) {
    self::doRequest([
      ['opcode', 20, Type::BYTE],
      ['delete', $delete, Type::BOOL],
      ['requestLength', 6, Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['property', $property, Type::ATOM],
      ['type', $type, Type::ATOM],
      ['longOffset', $longOffset, Type::CARD32],
      ['longLength', $longLength, Type::CARD32]
    ]);
    return Response::GetProperty();
  }

  public static function ListProperties($wid) {
    self::doRequest([
      ['opcode', 21, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['property', $property, Type::ATOM]
    ]);
    return Response::ListProperties();
  }

  public static function SetSelectionOwner($window, $selection, $timestamp) {
    self::doRequest([
      ['opcode', 22, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4, Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['selection', $selection, Type::ATOM],
      ['timestamp', $timestamp, Type::TIMESTAMP]
    ]);
  }

  public static function GetSelectionOwner($selection) {
    self::doRequest([
      ['opcode', 23, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['selection', $selection, Type::ATOM]
    ]);
    return Response::GetSelectionOwner();
  }

  public static function ConvertSelection($requestor, $selection, $target, $property, $timestamp) {
    self::doRequest([
      ['opcode', 24, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 6, Type::CARD16],
      ['requestor', $requestor, Type::WINDOW],
      ['selection', $selection, Type::ATOM],
      ['target', $target, Type::ATOM],
      ['property', $property, Type::ATOM],
      ['timestamp', $timestamp, Type::TIMESTAMP]
    ]);
  }

  public static function SendEvent($propagate, $destination, $eventMask, $event) {
    self::doRequest([
      ['opcode', 25, Type::BYTE],
      ['propagate', 0, Type::BOOL],
      ['requestLength', 11, Type::CARD16],
      ['destination', $destination, Type::WINDOW],
      ['eventMask', $eventMask, Type::CARD32],
      ['event', $event, Type::EVENT]
    ]);
  }

  public static function GrabPointer(
    $grabWindow, $eventMask, $pointerMode, $keyboardMode,
    $confineTo, $cursor, $timestamp
  ) {
    self::doRequest([
      ['opcode', 26, Type::BYTE],
      ['ownerEvents', 0, Type::BOOL],
      ['requestLength', 6, Type::CARD16],
      ['grabWindow', $grabWindow, Type::WINDOW],
      ['eventMask', $eventMask, Type::CARD16],
      ['pointerMode', $pointerMode, Type::ENUM8, ['Synchronous', 'Asynchronous']],
      ['keyboardMode', $keyboardMode, Type::ENUM8, ['Synchronous', 'Asynchronous']],
      ['confineTo', $confineTo, Type::WINDOW],
      ['cursor', $cursor, Type::CURSOR],
      ['timestamp', $timestamp, Type::CARD32]
    ]);
    return Response::GrabKeyboard();
  }


  public static function UngrabPointer($timestamp) {
    self::doRequest([
      ['opcode', 27, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['timestamp', $timestamp, Type::TIMESTAMP]
    ]);
  }

  public static function GrabButton($ownerEvents, $grabWindow, $eventMask, $pointerMode, $keyboardMode, $confineTo, $cursor, $button, $modifiers) {
    self::doRequest([
      ['opcode', 28, Type::BYTE],
      ['ownerEvents', $ownerEvents, Type::BOOL],
      ['requestLength', 6, Type::CARD16],
      ['grabWindow', $grabWindow, Type::WINDOW],
      ['EventMask', $eventMask, Type::CARD16],
      ['pointerMode', $pointerMode, Type::ENUM8, ['Synchronous', 'Asynchronous']],
      ['keyboardMode', $pointerMode, Type::ENUM8, ['Synchronous', 'Asynchronous']],
      ['confineTo', $confineTo, Type::WINDOW],
      ['cursor', $cursor, Type::CURSOR],
      ['button', $button, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['modifiers', $modifiers, Type::CARD16]
    ]);
  }

  public static function UngrabButton($button, $window, $modifiers) {
    self::doRequest([
      ['opcode', 29, Type::BYTE],
      ['button', $button, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['modifiers', $modifiers, Type::CARD16],
      ['unused', 0, Type::CARD16]
    ]);
  }

  public static function ChangeActivePointerGrab($cursor, $timestamp, $eventMask) {
    self::doRequest([
      ['opcode', 30, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4, Type::CARD16],
      ['cursor', $cursor, Type::CURSOR],
      ['timestamp', $timestamp, Type::CARD32],
      ['eventMask', $eventMask, Type::CARD16],
      ['unused', 0, Type::CARD16]
    ]);
  }

  public static function GrabKeyboard($ownerEvents, $grabWindow, $timestamp, $pointerMode, $keyboardMode) {
    self::doRequest([
      ['opcode', 31, Type::BYTE],
      ['ownerEvents', $ownerEvents, Type::BOOL],
      ['requestLength', 4, Type::CARD16],
      ['grabWindow', $grabWindow, Type::WINDOW],
      ['timestamp', $timestamp, Type::CARD32],
      ['pointerMode', $pointerMode, Type::ENUM8, ['Synchronous', 'Asynchronous']],
      ['keyboardMode', $pointerMode, Type::ENUM8, ['Synchronous', 'Asynchronous']],
      ['unused', 0, Type::BYTE]
    ]);
    return Response::GrabKeyboard();
  }

  public static function UngrabKeyboard() {
    self::doRequest([
      ['opcode', 32, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['timestamp', $timestamp, Type::CARD32]
    ]);
  }

  public static function GrabKey($ownerEvents, $grabWindow, $modifiers, $key, $pointerMode, $keyboardMode) {
    self::doRequest([
      ['opcode', 33, Type::BYTE],
      ['ownerEvents', $ownerEvents, Type::BOOL],
      ['requestLength', 4, Type::CARD16],
      ['grabWindow', $grabWindow, Type::WINDOW],
      ['modifiers', $modifiers, Type::CARD16],
      ['key', $key, Type::BYTE],
      ['pointerMode', $pointerMode, Type::ENUM8, ['Synchronous', 'Asynchronous']],
      ['keyboardMode', $pointerMode, Type::ENUM8, ['Synchronous', 'Asynchronous']],
      ['unused', 0, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['unused', 0, Type::BYTE]
    ]);
  }

  public static function UngrabKey($key, $timestamp, $grabWindow, $modifiers) {
    self::doRequest([
      ['opcode', 34, Type::BYTE],
      ['key', $key, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['timestamp', $timestamp, Type::CARD32],
      ['grabWindow', $grabWindow, Type::WINDOW],
      ['modifiers', $modifiers, Type::CARD16],
      ['unused', 0, Type::CARD16]
    ]);
  }


  public static function AllowEvents($mode, $timestamp) {
    self::doRequest([
      ['opcode', 35, Type::BYTE],
      ['mode', $mode, Type::ENUM8, ['AsyncPointer', 'SyncPointer', 'ReplayPointer', 'AsyncKeyboard', 'SyncKeyboard', 'ReplayKeyboard', 'AsyncBoth', 'SyncBoth']],
      ['requestLength', 2, Type::CARD16],
      ['timestamp', $timestamp, Type::CARD32]
    ]);
  }

  public static function GrabServer() {
    self::doRequest([
      ['opcode', 36, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 1, Type::CARD16]
    ]);
  }

  public static function UngrabServer() {
    self::doRequest([
      ['opcode', 37, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 1, Type::CARD16]
    ]);
  }

  public static function QueryPointer($window) {
    self::doRequest([
      ['opcode', 38, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['window', $window, Type::WINDOW]
    ]);
    return Response::QueryPointer();
  }

  public static function GetMotionEvents($window, $start, $stop) {
    self::doRequest([
      ['opcode', 39, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4, Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['start', $start, Type::CARD32],
      ['stop', $stop, Type::CARD32]
    ]);
    return Response::GetMotionEvents();
  }

  public static function TranslateCoordinates($window, $srcX, $srcY) {
    self::doRequest([
      ['opcode', 40, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4, Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['srcX', $srcX, Type::INT16],
      ['srcY', $srcY, Type::INT16]
    ]);
    return Response::TranslateCoordinates();
  }

  public static function WarpPointer($srcWindow, $dstWindow, $srcX, $srcY, $srcWidth, $srcHeight, $dstX, $dstY) {
    self::doRequest([
      ['opcode', 41, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 6, Type::CARD16],
      ['srcWindow', $srcWindow, Type::WINDOW],
      ['dstWindow', $dstWindow, Type::WINDOW],
      ['srcX', $srcX, Type::INT16],
      ['srcY', $srcY, Type::INT16],
      ['srcWidth', $srcWidth, Type::CARD16],
      ['srcHeight', $srcHeight, Type::CARD16],
      ['dstX', $srcX, Type::INT16],
      ['dstY', $srcY, Type::INT16]
    ]);
  }

  public static function SetInputFocus($revertTo, $window, $timestamp) {
    self::doRequest([
      ['opcode', 42, Type::BYTE],
      ['revertTo', $revertTo, Type::ENUM8, ['None', 'PointerRoot', 'Parent']],
      ['requestLength', 3, Type::CARD16],
      ['srcWindow', $srcWindow, Type::WINDOW],
      ['timestamp', $timestamp, Type::CARD32]
    ]);
  }

  public static function GetInputFocus() {
    self::doRequest([
      ['opcode', 43, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 1, Type::CARD16]
    ]);
    return Response::GetInputFocus();
  }

  public static function QueryKeymap() {
    self::doRequest([
      ['opcode', 44, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 1, Type::CARD16]
    ]);
    return Response::QueryKeymap();
  }

  public static function OpenFont($fid, $name) {
    $length = strlen($name);
    self::doRequest([
      ['opcode', 45, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['fid', $fid, Type::FONT],
      ['lengthOfName', $length, Type::CARD16],
      ['unused', 0, Type::CARD16],
      ['name', $name, Type::STRING8],
      ['pad', Connection::pad4($length)]
    ]);
  }

  public static function CloseFont($fid) {
    self::doRequest([
      ['opcode', 46, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['fid', $fid, Type::FONT]
    ]);
  }

  public static function QueryFont($font) {
    self::doRequest([
      ['opcode', 47, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['font', $font, Type::FONT]
    ]);
    return Response::QueryFont();
  }

  public static function QueryTextExtents($font, $string) {
    $length = strlen($string);
    $pad = Connection::pad4($length);
    self::doRequest([
      ['opcode', 48, Type::BYTE],
      ['oddLength', $pad == 2, Type::BOOL],
      ['requestLength', 2, Type::CARD16],
      ['font', $font, Type::FONT],
      ['string', $string, Type::STRING8],
      ['pad', $pad, Type::PAD4]
    ]);
    return Response::QueryTextExtents();
  }

  public static function ListFonts($maxNames, $pattern) {
    $length = strlen($pattern);
    self::doRequest([
      ['opcode', 49, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['maxNames', $maxNames, Type::CARD16],
      ['lengthOfPattern', $length, Type::PAD4],
      ['pattern', $pattern, Type::STRING8],
      ['pad', Connection::pad4($length), Type::PAD4]
    ]);
    return Response::ListFonts();
  }

  public static function ListFontsWithInfo($maxNames, $pattern) {
    $length = strlen($pattern);
    self::doRequest([
      ['opcode', 50, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['maxNames', $maxNames, Type::CARD16],
      ['lengthOfPattern', $length, Type::PAD4],
      ['pattern', $pattern, Type::STRING8],
      ['pad', Connection::pad4($length), Type::PAD4]
    ]);
    return Response::ListFontsWithInfo();
  }

  public static function SetFontPath($path) {
    $length = strlen($path);
    self::doRequest([
      ['opcode', 51, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['numberOfStrings', $n, Type::CARD16],
      ['unused', 0, Type::CARD16],
      ['lengthOfPattern', $length, Type::PAD4],
      ['path', $path, Type::STRING8],
      ['pad', Connection::pad4($length), Type::PAD4]
    ]);

  }

  public static function GetFontPath() {
    self::doRequest([
      ['opcode', 52, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16]
    ]);
    return Response::GetFontPath();
  }

  public static function CreatePixmap($depth, $pid, $drawable, $width, $height) {
    self::doRequest([
      ['opcode', 53, Type::BYTE],
      ['depth', $depth, Type::CARD8],
      ['requestLength', 4, Type::CARD16],
      ['pid', $pid, Type::PIXMAP],
      ['drawable', $drawable, Type::DRAWABLE],
      ['width', $width, Type::CARD16],
      ['height', $height, Type::CARD16]
    ]);
  }

  public static function FreePixmap($pixmap) {
    self::doRequest([
      ['opcode', 54, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['pixmap', $pixmap, Type::PIXMAP]
    ]);
  }

  public static function CreateGC($cid, $drawable, $values) {
    $data = [
      ['opcode', 55, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4, Type::CARD16],
      ['cid', $cid, Type::GCCONTEXT],
      ['drawable', $drawable, Type::DRAWABLE]
    ];
    $valueMap = [
      'function' => [Type::ENUM8, ['Clear', 'And', 'AndReverse', 'Copy', 'AddInverted', 'NoOp', 'Xor', 'Or', 'Nor', 'Equiv', 'Invert', 'OrReverse', 'CopyInverted', 'OrInverted', 'Nand', 'Set']],
      'planeMask' => Type::CARD32,
      'foreground' => Type::CARD32,
      'background' => Type::CARD32,
      'lineWidth' => Type::CARD16,
      'lineStyle' => [Type::ENUM8, ['Solid', 'OnOffDash', 'DoubleDash']],
      'capStyle' => [Type::ENUM8, ['NotLast', 'Butt', 'Round', 'Projecting']],
      'joinStyle' => [Type::ENUM8, ['Mitter', 'Round', 'Bevel']],
      'fillStyle' => [Type::ENUM8, ['Solid', 'Tiled', 'Stippled', 'OpaqueStippled']],
      'fillRule' => [Type::ENUM8, ['EvenOdd', 'Winding']],
      'tile' => Type::PIXMAP,
      'stipple' => Type::PIXMAP,
      'tileStippleXOrigin' => Type::INT16,
      'tileStippleYOrigin' => Type::INT16,
      'font' => Type::FONT,
      'subwindowMode' => [Type::ENUM8, ['ClipByChildren', 'IncludeInferiors']],
      'graphicsExposures' => Type::BOOL,
      'clipXOrigin' => Type::INT16,
      'clipYOrigin' => Type::INT16,
      'clipMask' => Type::PIXMAP,
      'dashOffset' => Type::CARD16,
      'dashes' => Type::CARD8,
      'arcMode' => [Type::ENUM8, ['Chord', 'PieSlice']],
    ];
    $data = self::addBitmaskList($data, $valueMap, $values);
    self::doRequest($data);
  }

  public static function ChangeGC($cid, $values) {
    $data = [
      ['opcode', 56, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['cid', $cid, Type::GCCONTEXT],
    ];
    $valueMap = [
      'function' => [Type::ENUM8, ['Clear', 'And', 'AndReverse', 'Copy', 'AddInverted', 'NoOp', 'Xor', 'Or', 'Nor', 'Equiv', 'Invert', 'OrReverse', 'CopyInverted', 'OrInverted', 'Nand', 'Set']],
      'planeMask' => Type::CARD32,
      'foreground' => Type::CARD32,
      'background' => Type::CARD32,
      'lineWidth' => Type::CARD16,
      'lineStyle' => [Type::ENUM8, ['Solid', 'OnOffDash', 'DoubleDash']],
      'capStyle' => [Type::ENUM8, ['NotLast', 'Butt', 'Round', 'Projecting']],
      'joinStyle' => [Type::ENUM8, ['Mitter', 'Round', 'Bevel']],
      'fillStyle' => [Type::ENUM8, ['Solid', 'Tiled', 'Stippled', 'OpaqueStippled']],
      'fillRule' => [Type::ENUM8, ['EvenOdd', 'Winding']],
      'tile' => Type::PIXMAP,
      'stipple' => Type::PIXMAP,
      'tileStippleXOrigin' => Type::INT16,
      'tileStippleYOrigin' => Type::INT16,
      'font' => Type::FONT,
      'subwindowMode' => [Type::ENUM8, ['ClipByChildren', 'IncludeInferiors']],
      'graphicsExposures' => Type::BOOL,
      'clipXOrigin' => Type::INT16,
      'clipYOrigin' => Type::INT16,
      'clipMask' => Type::PIXMAP,
      'dashOffset' => Type::CARD16,
      'dashes' => Type::CARD8,
      'arcMode' => [Type::ENUM8, ['Chord', 'PieSlice']],
    ];
    $data = self::addBitmaskList($data, $valueMap, $values);
    self::doRequest($data);
  }

  public static function CopyGC($srcGc, $dstGc, $values) {
    $data = [
      ['opcode', 57, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4, Type::CARD16],
      ['srcGc', $srcGc, Type::GCCONTEXT],
      ['dstGc', $dstGc, Type::GCCONTEXT]
    ];
    $valueMap = [
      'function' => [Type::ENUM8, ['Clear', 'And', 'AndReverse', 'Copy', 'AddInverted', 'NoOp', 'Xor', 'Or', 'Nor', 'Equiv', 'Invert', 'OrReverse', 'CopyInverted', 'OrInverted', 'Nand', 'Set']],
      'planeMask' => Type::CARD32,
      'foreground' => Type::CARD32,
      'background' => Type::CARD32,
      'lineWidth' => Type::CARD16,
      'lineStyle' => [Type::ENUM8, ['Solid', 'OnOffDash', 'DoubleDash']],
      'capStyle' => [Type::ENUM8, ['NotLast', 'Butt', 'Round', 'Projecting']],
      'joinStyle' => [Type::ENUM8, ['Mitter', 'Round', 'Bevel']],
      'fillStyle' => [Type::ENUM8, ['Solid', 'Tiled', 'Stippled', 'OpaqueStippled']],
      'fillRule' => [Type::ENUM8, ['EvenOdd', 'Winding']],
      'tile' => Type::PIXMAP,
      'stipple' => Type::PIXMAP,
      'tileStippleXOrigin' => Type::INT16,
      'tileStippleYOrigin' => Type::INT16,
      'font' => Type::FONT,
      'subwindowMode' => [Type::ENUM8, ['ClipByChildren', 'IncludeInferiors']],
      'graphicsExposures' => Type::BOOL,
      'clipXOrigin' => Type::INT16,
      'clipYOrigin' => Type::INT16,
      'clipMask' => Type::PIXMAP,
      'dashOffset' => Type::CARD16,
      'dashes' => Type::CARD8,
      'arcMode' => [Type::ENUM8, ['Chord', 'PieSlice']],
    ];
    $data = self::addBitmaskList($data, $valueMap, $values);
    self::doRequest($data);
  }

  public static function SetDashes($gc, $dashOffset, $dashes) {
    $length = strlen($dashes);
    self::doRequest([
      ['opcode', 58, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['gc', $gc, Type::GCCONTEXT],
      ['dashOffset', $dashOffset, Type::CARD16],
      ['n', $length, Type::CARD16],
      ['dashes', $dashes, Type::STRING8],
      ['pad', Connection::pad4($length), Type::PAD4]
    ]);
  }

  public static function SetClipRectangles($ordering, $gc, $clipXOrigin, $clipYOrigin, $rectangles) {
    $data = [
      ['opcode', 59, Type::BYTE],
      ['ordering', $ordering, Type::ENUM8, ['UnSorted', 'YSorted', 'YXSorted', 'YXBanded']],
      ['requestLength', 3 + 2 * count($rectangles), Type::CARD16],
      ['gc', $gc, Type::GCCONTEXT],
      ['clipXOrigin', $clipXOrigin, Type::INT16],
      ['clipYOrigin', $clipYOrigin, Type::INT16],
      ['dashes', $dashes, Type::STRING8]
    ];
    foreach ($rectangles as $rectangle) {
      $data[] = ['x', $rectangle['x'], Type::INT16];
      $data[] = ['y', $rectangle['y'], Type::INT16];
      $data[] = ['width', $rectangle['width'], Type::CARD16];
      $data[] = ['height', $rectangle['height'], Type::CARD16];
    }
    self::doRequest($data);
  }

  public static function FreeGC($gc) {
    self::doRequest([
      ['opcode', 60, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['gc', $gc, Type::GCCONTEXT]
    ]);
  }

  public static function ClearArea($exposures, $window, $x, $y, $width, $height) {
    self::doRequest([
      ['opcode', 61, Type::BYTE],
      ['exposures', $exposures, Type::BOOL],
      ['requestLength', 4, Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['x', $x, Type::INT16],
      ['y', $y, Type::INT16],
      ['width', $width, Type::CARD16],
      ['height', $height, Type::CARD16]
    ]);
  }

  public static function CopyArea($srcDrawable, $dstDrawable, $gc, $srcX, $srcY, $dstX, $dstY, $width, $height) {
    self::doRequest([
      ['opcode', 62, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 7, Type::CARD16],
      ['srcDrawable', $srcDrawable, Type::DRAWABLE],
      ['dstDrawable', $dstDrawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCCONTEXT],
      ['srcX', $srcX, Type::INT16],
      ['srcY', $srcY, Type::INT16],
      ['dstX', $dstX, Type::INT16],
      ['dstY', $dstY, Type::INT16],
      ['width', $width, Type::CARD16],
      ['height', $height, Type::CARD16]
    ]);
  }

  public static function CopyPlane($srcDrawable, $dstDrawable, $gc, $srcX, $srcY, $dstX, $dstY, $width, $height, $bitPlane) {
    self::doRequest([
      ['opcode', 63, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 8, Type::CARD16],
      ['srcDrawable', $srcDrawable, Type::DRAWABLE],
      ['dstDrawable', $dstDrawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCCONTEXT],
      ['srcX', $srcX, Type::INT16],
      ['srcY', $srcY, Type::INT16],
      ['dstX', $dstX, Type::INT16],
      ['dstY', $dstY, Type::INT16],
      ['width', $width, Type::CARD16],
      ['height', $height, Type::CARD16],
      ['bitPlane', $bitPlane, Type::CARD32]
    ]);
  }

  public static function PolyPoint($coordinateMode, $drawable, $gc, $points) {
    $data = [
      ['opcode', 64, Type::BYTE],
      ['coordinateMode', $coordinateMode, Type::ENUM8, ['Origin', 'Previous']],
      ['requestLength', 3 + count($points), Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCCONTEXT],
    ];
    foreach ($points as $point) {
      $data[] = ['x', $point['x'], Type::INT16];
      $data[] = ['y', $point['y'], Type::INT16];
    }
    self::doRequest($data);
  }


  public static function PolyLine($coordinateMode, $drawable, $gc, $points) {
    $data = [
      ['opcode', 65, Type::BYTE],
      ['coordinateMode', $coordinateMode, Type::ENUM8, ['Origin', 'Previous']],
      ['requestLength', 3 + count($points), Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCCONTEXT],
    ];
    foreach ($points as $point) {
      $data[] = ['x', $point['x'], Type::INT16];
      $data[] = ['y', $point['y'], Type::INT16];
    }
    self::doRequest($data);
  }

  public static function PolySegment($drawable, $gc, $segments) {
    $data = [
      ['opcode', 66, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3 + 2 * count($segments), Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCCONTEXT],
    ];
    foreach ($segments as $segment) {
      $data[] = ['x1', $segment['x1'], Type::INT16];
      $data[] = ['y1', $segment['y1'], Type::INT16];
      $data[] = ['x2', $segment['x2'], Type::INT16];
      $data[] = ['y2', $segment['y2'], Type::INT16];
    }
    self::doRequest($data);
  }

  public static function PolyRectangle($drawable, $gc, $rectangles) {
    $data = [
      ['opcode', 67, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3 + 2 * count($rectangles), Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCCONTEXT],
    ];
    foreach ($rectangles as $rectangle) {
      $data[] = ['x', $rectangle['x'], Type::INT16];
      $data[] = ['y', $rectangle['y'], Type::INT16];
      $data[] = ['width', $rectangle['width'], Type::CARD16];
      $data[] = ['height', $rectangle['height'], Type::CARD16];
    }
    self::doRequest($data);
  }

  public static function PolyArc($drawable, $gc, $arcs) {
    $data = [
      ['opcode', 68, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3 + 3 * count($arcs), Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCCONTEXT],
    ];
    foreach ($arcs as $arc) {
      $data[] = ['x', $arc['x'], Type::INT16];
      $data[] = ['y', $arc['y'], Type::INT16];
      $data[] = ['width', $arc['width'], Type::CARD16];
      $data[] = ['height', $arc['height'], Type::CARD16];
      $data[] = ['angle1', $arc['angle1'], Type::INT16];
      $data[] = ['angle2', $arc['angle2'], Type::INT16];
    }
    self::doRequest($data);
  }

  public static function FillPoly($drawable, $gc, $shape, $coordinateMode, $points) {
    $data = [
      ['opcode', 69, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4 + count($points), Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCCONTEXT],
      ['shape', $shape, Type::ENUM, ['Complex', 'Nonconvex', 'Convex']],
      ['coordinateMode', $coordinateMode, Type::ENUM, ['Origin', 'Previous']],
      ['unused', 0, Type::CARD16]
    ];
    foreach ($points as $point) {
      $data[] = ['x', $point['x'], Type::INT16];
      $data[] = ['y', $point['y'], Type::INT16];
    }
    self::doRequest($data);
  }

  public static function PolyFillRectangle($drawable, $gc, $rectangles) {
    $data = [
      ['opcode', 70, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3 + 2 * count($rectangles), Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCCONTEXT],
    ];
    foreach ($rectangles as $rectangle) {
      $data[] = ['x', $rectangle['x'], Type::INT16];
      $data[] = ['y', $rectangle['y'], Type::INT16];
      $data[] = ['width', $rectangle['width'], Type::CARD16];
      $data[] = ['height', $rectangle['height'], Type::CARD16];
    }
    self::doRequest($data);
  }

  public static function PolyFillArc($drawable, $gc, $arcs) {
    $data = [
      ['opcode', 71, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3 + 3 * count($arcs), Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCCONTEXT],
    ];
    foreach ($arcs as $arc) {
      $data[] = ['x', $arc['x'], Type::INT16];
      $data[] = ['y', $arc['y'], Type::INT16];
      $data[] = ['width', $arc['width'], Type::CARD16];
      $data[] = ['height', $arc['height'], Type::CARD16];
      $data[] = ['angle1', $arc['angle1'], Type::INT16];
      $data[] = ['angle2', $arc['angle2'], Type::INT16];
    }
    self::doRequest($data);
  }

  public static function PutImage($format, $drawable, $gc, $width, $height, $dstX, $dstY, $leftPad, $depth, $imageData) {
    $length = strlen($imageData);
    $pad = Connection::pad4($length);
    self::doRequest([
      ['opcode', 72, Type::BYTE],
      ['format', $format, Type::ENUM8, ['Bitmap', 'XYPixmap', 'ZPixmap']],
      ['requestLength', 6 + (($length + $pad) >> 2), Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCCONTEXT],
      ['width', $width, Type::CARD16],
      ['height', $height, Type::CARD16],
      ['dstX', $dstX, Type::INT16],
      ['dstY', $dstY, Type::INT16],
      ['leftPad', $depth, Type::CARD8],
      ['unused', 0, Type::CARD16],
      ['data', $imageData, Type::STRING8],
      ['pad', $pad, Type::PAD4]
    ]);
  }


  public static function GetImage($format, $drawable, $x, $y, $width, $height, $planeMask) {
    self::doRequest([
      ['opcode', 73, Type::BYTE],
      ['format', $format, Type::ENUM8, ['XYPixmap', 'ZPixmap']],
      ['requestLength', 2, Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['x', $x, Type::INT16],
      ['y', $y, Type::INT16],
      ['width', $width, Type::CARD16],
      ['height', $height, Type::CARD16],
      ['planeMask', $planeMask, Type::CARD32]
    ]);
    return Response::GetImage();
  }


  public static function PolyText8($drawable, $gc, $texts) {
    self::doRequest([
      ['opcode', 74, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4, Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCCONTEXT],
      ['x', $x, Type::INT16],
      ['y', $y, Type::INT16]
//???
/*
     n     LISTofTEXTITEM8                 items
     p                                     unused, p=pad(n)  (p is always 0
                                           or 1)

  TEXTITEM8
     1     m                               length of string (cannot be 255)
     1     INT8                            delta
     m     STRING8                         string
  or
     1     255                             font-shift indicator
     1                                     font byte 3 (most-significant)
     1                                     font byte 2
     1                                     font byte 1
     1                                     font byte 0 (least-significant)
*/
    ]);
  }
/*
  public static function PolyText16() {
     1     75                              opcode
     1                                     unused
     2     4+(n+p)/4                       request length
     4     DRAWABLE                        drawable
     4     GCONTEXT                        gc
     2     INT16                           x
     2     INT16                           y
     n     LISTofTEXTITEM16                items
     p                                     unused, p=pad(n)  (p must be 0 or
                                           1)

  TEXTITEM16
     1     m                               number of CHAR2Bs in string
                                           (cannot be 255)
     1     INT8                            delta
     2m     STRING16                       string
  or
     1     255                             font-shift indicator
     1                                     font byte 3 (most-significant)
     1                                     font byte 2
     1                                     font byte 1
     1                                     font byte 0 (least-significant)

  }
*/

  public static function ImageText8($drawable, $gc, $x, $y, $string) {
    self::doRequest([
      ['opcode', 76, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4, Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCCONTEXT],
      ['x', $x, Type::INT16],
      ['y', $y, Type::INT16],
      ['string', $string, Type::STRING8],
      ['pad', Connection::pad4(strlen($string)), Type::PAD4]
    ]);
  }

  public static function ImageText16($drawable, $gc, $x, $y, $string) {
    self::doRequest([
      ['opcode', 77, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4, Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCCONTEXT],
      ['x', $x, Type::INT16],
      ['y', $y, Type::INT16],
      ['string', $string, Type::STRING8],
      ['pad', Connection::pad4(strlen($string)), Type::PAD4]
    ]);
  }

  public static function CreateColormap($alloc, $window, $visual) {
    self::doRequest([
      ['opcode', 78, Type::BYTE],
      ['alloc', 0, Type::ENUM8, ['None', 'All']],
      ['requestLength', 4, Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['visual', $visual, Type::VISUALID],
    ]);
  }

  public static function FreeColormap($window, $colormap) {
    self::doRequest([
      ['opcode', 79, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4, Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['cmap', $colormap, Type::VISUALID],
    ]);
  }

  public static function CopyColormapAndFree($mid, $srcColormap) {
    self::doRequest([
      ['opcode', 80, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['mid', $mid, Type::COLORMAP],
      ['srcCmap', $srcColormap, Type::COLORMAP],
    ]);
  }

  public static function InstallColormap($colormap) {
    self::doRequest([
      ['opcode', 81, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['colormap', $colormap, Type::COLORMAP]
    ]);
  }

  public static function UninstallColormap($colormap) {
    self::doRequest([
      ['opcode', 82, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['colormap', $colormap, Type::COLORMAP]
    ]);
  }

  public static function ListInstalledColormaps($window) {
    self::doRequest([
      ['opcode', 83, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['window', $window, Type::WINDOW]
    ]);
    return Response::ListInstalledColormaps();
  }


  public static function AllocColor($colormap, $red, $green, $blue) {
    self::doRequest([
      ['opcode', 84, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4, Type::CARD16],
      ['colormap', $colormap, Type::COLORMAP],
      ['red', $red, Type::CARD16],
      ['green', $green, Type::CARD16],
      ['blue', $blue, Type::CARD16],
      ['unused', 0, Type::CARD16],
    ]);
    return Response::AllocColor();
  }


  public static function AllocNamedColor($name) {
    $length = strlen($name);
    self::doRequest([
      ['opcode', 85, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['length', $length, Type::CARD16],
      ['unused', 0, Type::CARD16],
      ['name', $name, Type::STRING8],
      ['pad', Connection::pad4($length), Type::PAD4],
    ]);
    return Response::AllocNamedColor();
  }



  public static function AllocColorCells($continguous, $colormap, $colors, $planes) {
    self::doRequest([
      ['opcode', 86, Type::BYTE],
      ['continguous', $continguous, Type::BOOL],
      ['requestLength', 3, Type::CARD16],
      ['colormap', $colormap, Type::COLORMAP],
      ['colors', $colors, Type::CARD16],
      ['planes', $planes, Type::CARD16]
    ]);
    return Response::AllocColorCells();
  }

  public static function AllocColorPlanes($continguous, $colormap, $colors, $reds, $greens, $blues) {
    self::doRequest([
      ['opcode', 87, Type::BYTE],
      ['continguous', $continguous, Type::BOOL],
      ['requestLength', 4, Type::CARD16],
      ['colormap', $colormap, Type::COLORMAP],
      ['colors', $colors, Type::CARD16],
      ['reds', $reds, Type::CARD16],
      ['greens', $greens, Type::CARD16],
      ['bluess', $blues, Type::CARD16]
    ]);
    return Response::AllocColorPlanes();

  }


  public static function FreeColors($colormap, $planeMask, $pixels) {
    $data = [
      ['opcode', 88, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3 + count($pixels), Type::CARD16],
      ['colormap', $colormap, Type::COLORMAP],
      ['planeMask', $planeMask, Type::CARD32]
    ];
    foreach ($pixels as $pixel) {
      $data[] = ['pixel', $pixel, Type::CARD32];
    }
    self::doRequest($data);
  }

/*
  public static function StoreColors() {
     1     89                              opcode
     1                                     unused
     2     2+3n                            request length
     4     COLORMAP                        cmap
     12n     LISTofCOLORITEM               items

  COLORITEM
     4     CARD32                          pixel
     2     CARD16                          red
     2     CARD16                          green
     2     CARD16                          blue
     1                                     do-red, do-green, do-blue
          #x01     do-red (1 is True, 0 is False)
          #x02     do-green (1 is True, 0 is False)
          #x04     do-blue (1 is True, 0 is False)
          #xF8     unused
     1                                     unused

  }

  public static function StoreNamedColor() {
     1     90                              opcode
     1                                     do-red, do-green, do-blue
          #x01     do-red (1 is True, 0 is False)
          #x02     do-green (1 is True, 0 is False)
          #x04     do-blue (1 is True, 0 is False)
          #xF8     unused
     2     4+(n+p)/4                       request length
     4     COLORMAP                        cmap
     4     CARD32                          pixel
     2     n                               length of name
     2                                     unused
     n     STRING8                         name
     p                                     unused, p=pad(n)

  }

  public static function QueryColors() {
     1     91                              opcode
     1                                     unused
     2     2+n                             request length
     4     COLORMAP                        cmap
     4n     LISTofCARD32                   pixels

▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     2n                              reply length
     2     n                               number of RGBs in colors
     22                                    unused
     8n     LISTofRGB                      colors

  RGB
     2     CARD16                          red
     2     CARD16                          green
     2     CARD16                          blue
     2                                     unused

  }

  public static function LookupColor() {
     1     92                              opcode
     1                                     unused
     2     3+(n+p)/4                       request length
     4     COLORMAP                        cmap
     2     n                               length of name
     2                                     unused
     n     STRING8                         name
     p                                     unused, p=pad(n)

▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     0                               reply length
     2     CARD16                          exact-red
     2     CARD16                          exact-green
     2     CARD16                          exact-blue
     2     CARD16                          visual-red
     2     CARD16                          visual-green
     2     CARD16                          visual-blue
     12                                    unused

  }

  public static function CreateCursor() {
     1     93                              opcode
     1                                     unused
     2     8                               request length
     4     CURSOR                          cid
     4     PIXMAP                          source
     4     PIXMAP                          mask
          0     None
     2     CARD16                          fore-red
     2     CARD16                          fore-green
     2     CARD16                          fore-blue
     2     CARD16                          back-red
     2     CARD16                          back-green
     2     CARD16                          back-blue
     2     CARD16                          x
     2     CARD16                          y

  }

  public static function CreateGlyphCursor() {
     1     94                              opcode
     1                                     unused
     2     8                               request length
     4     CURSOR                          cid
     4     FONT                            source-font
     4     FONT                            mask-font
          0     None
     2     CARD16                          source-char
     2     CARD16                          mask-char
     2     CARD16                          fore-red
     2     CARD16                          fore-green
     2     CARD16                          fore-blue
     2     CARD16                          back-red
     2     CARD16                          back-green
     2     CARD16                          back-blue

  }

  public static function FreeCursor() {
     1     95                              opcode
     1                                     unused
     2     2                               request length
     4     CURSOR                          cursor

  }

  public static function RecolorCursor() {
     1     96                              opcode
     1                                     unused
     2     5                               request length
     4     CURSOR                          cursor
     2     CARD16                          fore-red
     2     CARD16                          fore-green
     2     CARD16                          fore-blue
     2     CARD16                          back-red
     2     CARD16                          back-green
     2     CARD16                          back-blue

  }

  public static function QueryBestSize() {
     1     97                              opcode
     1                                     class
          0     Cursor
          1     Tile
          2     Stipple
     2     3                               request length
     4     DRAWABLE                        drawable
     2     CARD16                          width
     2     CARD16                          height

▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     0                               reply length
     2     CARD16                          width
     2     CARD16                          height
     20                                    unused

  }

  public static function QueryExtension() {
     1     98                              opcode
     1                                     unused
     2     2+(n+p)/4                       request length
     2     n                               length of name
     2                                     unused
     n     STRING8                         name
     p                                     unused, p=pad(n)

▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     0                               reply length
     1     BOOL                            present
     1     CARD8                           major-opcode
     1     CARD8                           first-event
     1     CARD8                           first-error
     20                                    unused

  }

  public static function ListExtensions() {
     1     99                              opcode
     1                                     unused
     2     1                               request length

▶
     1     1                               Reply
     1     CARD8                           number of STRs in names
     2     CARD16                          sequence number
     4     (n+p)/4                         reply length
     24                                    unused
     n     LISTofSTR                       names
     p                                     unused, p=pad(n)

  }

  public static function ChangeKeyboardMapping() {
     1     100                             opcode
     1     n                               keycode-count
     2     2+nm                            request length
     1     KEYCODE                         first-keycode
     1     m                               keysyms-per-keycode
     2                                     unused
     4nm     LISTofKEYSYM                  keysyms

  }

  public static function GetKeyboardMapping() {
     1     101                             opcode
     1                                     unused
     2     2                               request length
     1     KEYCODE                         first-keycode
     1     m                               count
     2                                     unused

▶
     1     1                               Reply
     1     n                               keysyms-per-keycode
     2     CARD16                          sequence number
     4     nm                              reply length (m = count field
                                           from the request)
     24                                    unused
     4nm     LISTofKEYSYM                  keysyms

  }

  public static function ChangeKeyboardControl() {
     1     102                             opcode
     1                                     unused
     2     2+n                             request length
     4     BITMASK                         value-mask (has n bits set to 1)
          #x0001     key-click-percent
          #x0002     bell-percent
          #x0004     bell-pitch
          #x0008     bell-duration
          #x0010     led
          #x0020     led-mode
          #x0040     key
          #x0080     auto-repeat-mode
     4n     LISTofVALUE                    value-list

  VALUEs
     1     INT8                            key-click-percent
     1     INT8                            bell-percent
     2     INT16                           bell-pitch
     2     INT16                           bell-duration
     1     CARD8                           led
     1                                     led-mode
          0     Off
          1     On
     1     KEYCODE                         key
     1                                     auto-repeat-mode
          0     Off
          1     On
          2     Default

  }

  public static function GetKeyboardControl() {
     1     103                             opcode
     1                                     unused
     2     1                               request length

▶
     1     1                               Reply
     1                                     global-auto-repeat
          0     Off
          1     On
     2     CARD16                          sequence number
     4     5                               reply length
     4     CARD32                          led-mask
     1     CARD8                           key-click-percent
     1     CARD8                           bell-percent
     2     CARD16                          bell-pitch
     2     CARD16                          bell-duration
     2                                     unused
     32     LISTofCARD8                    auto-repeats

  }

  public static function Bell() {
     1     104                             opcode
     1     INT8                            percent
     2     1                               request length

  }

  public static function ChangePointerControl() {
     1     105                             opcode
     1                                     unused
     2     3                               request length
     2     INT16                           acceleration-numerator
     2     INT16                           acceleration-denominator
     2     INT16                           threshold
     1     BOOL                            do-acceleration
     1     BOOL                            do-threshold

  }

  public static function GetPointerControl() {
     1     106                             opcode
     1                                     unused
     2     1                               request length

▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     0                               reply length
     2     CARD16                          acceleration-numerator
     2     CARD16                          acceleration-denominator
     2     CARD16                          threshold
     18                                    unused

  }

  public static function SetScreenSaver() {
     1     107                             opcode
     1                                     unused
     2     3                               request length
     2     INT16                           timeout
     2     INT16                           interval
     1                                     prefer-blanking
          0     No
          1     Yes
          2     Default
     1                                     allow-exposures
          0     No
          1     Yes
          2     Default
     2                                     unused

  }

  public static function GetScreenSaver() {
     1     108                             opcode
     1                                     unused
     2     1                               request length

▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     0                               reply length
     2     CARD16                          timeout
     2     CARD16                          interval
     1                                     prefer-blanking
          0     No
          1     Yes
     1                                     allow-exposures
          0     No
          1     Yes
     18                                    unused

  }

  public static function ChangeHosts() {
     1     109                             opcode
     1                                     mode
          0     Insert
          1     Delete
     2     2+(n+p)/4                       request length
     1                                     family
          0     Internet
          1     DECnet
          2     Chaos
     1                                     unused
     2     n                               length of address
     n     LISTofCARD8                     address
     p                                     unused, p=pad(n)

  }

  public static function ListHosts() {
     1     110                             opcode
     1                                     unused
     2     1                               request length

▶
     1     1                               Reply
     1                                     mode
          0     Disabled
          1     Enabled
     2     CARD16                          sequence number
     4     n/4                             reply length
     2     CARD16                          number of HOSTs in hosts
     22                                    unused
     n     LISTofHOST                      hosts (n always a multiple of 4)

  }

  public static function SetAccessControl() {
     1     111                             opcode
     1                                     mode
          0     Disable
          1     Enable
     2     1                               request length

  }

  public static function SetCloseDownMode() {
     1     112                             opcode
     1                                     mode
          0     Destroy
          1     RetainPermanent
          2     RetainTemporary
     2     1                               request length

  }

  public static function KillClient() {
     1     113                             opcode
     1                                     unused
     2     2                               request length
     4     CARD32                          resource
          0     AllTemporary

  }

  public static function RotateProperties() {
     1     114                             opcode
     1                                     unused
     2     3+n                             request length
     4     WINDOW                          window
     2     n                               number of properties
     2     INT16                           delta
     4n    LISTofATOM                      properties

  }

  public static function ForceScreenSaver() {
     1     115                             opcode
     1                                     mode
          0     Reset
          1     Activate
     2     1                               request length

  }

  public static function SetPointerMapping() {
     1     116                             opcode
     1     n                               length of map
     2     1+(n+p)/4                       request length
     n     LISTofCARD8                     map
     p                                     unused, p=pad(n)

▶
     1     1                               Reply
     1                                     status
          0     Success
          1     Busy
     2     CARD16                          sequence number
     4     0                               reply length
     24                                    unused

  }

  public static function GetPointerMapping() {
     1     117                             opcode
     1                                     unused
     2     1                               request length

▶
     1     1                               Reply
     1     n                               length of map
     2     CARD16                          sequence number
     4     (n+p)/4                         reply length
     24                                    unused
     n     LISTofCARD8                     map
     p                                     unused, p=pad(n)

  }

  public static function SetModifierMapping() {
     1     118                             opcode
     1     n                               keycodes-per-modifier
     2     1+2n                            request length
     8n    LISTofKEYCODE                   keycodes

▶
     1     1                               Reply
     1                                     status
          0     Success
          1     Busy
          2     Failed
     2     CARD16                          sequence number
     4     0                               reply length
     24                                    unused

  }

  public static function GetModifierMapping() {
     1     119                             opcode
     1                                     unused
     2     1                               request length

▶
     1     1                               Reply
     1     n                               keycodes-per-modifier
     2     CARD16                          sequence number
     4     2n                              reply length
     24                                    unused
     8n     LISTofKEYCODE                  keycodes

  }

  public static function NoOperation() {
     1     127                             opcode
     1                                     unused
     2     1+n                             request length
     4n                                    unused

  }
*/
}
