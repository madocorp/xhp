<?php

namespace X11;

class Response {

  protected static function debug($response) {
    foreach ($response as $name => $value) {
      echo '▶  ', $name, ': ', $value, "\n";
    }
    echo "\n";
  }

  protected static function receiveResponse($specification) {
    $length = 0;
    $formatString = [];
    $enums = [];
    $bools = [];
    foreach ($specification as $field) {
      $name = $field[0];
      $type = $field[1];
      $format = Type::$format[$type];
      if ($type == Type::STRING8) {
        $stringLength = $field[2];
        $format = str_replace('*', $stringLength, $format);
        $formatString[] = "{$format}{$name}";
        $length += $stringLength;
        $pad = Connection::pad4($stringLength);
        if ($pad > 0) {
          $formatString[] = "x{$pad}";
          $length += $pad;
        }
      } else {
        $formatString[] = "{$format}{$name}";
        $length += Type::$size[$format];
      }
      if (in_array($type, [Type::ENUM8, Type::ENUM16, Type::ENUM32])) {
        $enums[$name] = $field[2];
      }
      if ($type == Type::BOOL) {
        $bools[] = $name;
      }
    }
    $formatString = implode('/', $formatString);
    $response = Connection::read($length);
    $response = unpack($formatString, $response);
    foreach ($enums as $name => $values) {
      $response[$name] = $values[$response[$name]];
    }
    foreach ($bools as $name) {
      $response[$name] = ($response[$name] > 0);
    }
    if (count($response) == 1) {
      return reset($response);
    }
    unset($response['unused']);
    if (DEBUG) {
      self::debug($response);
    }
    return $response;
  }

  protected static function ConnectionFailed() {
    echo "Connection failed\n";
    $response = self::receiveResponse([
      ['lengthOfReason', Type::BYTE],
      ['protocolMajorVersion', Type::CARD16],
      ['protocolMinorVersion', Type::CARD16],
      ['fullLength', Type::CARD16]
    ]);
    $reason = self::receiveResponse([
      ['reason', Type::STRING8, $response['lengthOfReason']]
    ]);
    throw new \Exception("Connection failed: {$reason}");
  }

  protected static function ConnectionSuccess() {
    $response = self::receiveResponse([
      ['unused', Type::BYTE],
      ['protocolMajorVersion', Type::CARD16],
      ['protocolMinorVersion', Type::CARD16],
      ['additionalDataLength', Type::CARD16],
      ['releaseNumber', Type::CARD32],
      ['resourceIdBase', Type::CARD32],
      ['resourceIdMask', Type::CARD32],
      ['motionBufferSize', Type::CARD32],
      ['lengthOfVendor', Type::CARD16],
      ['maximumRequestLength', Type::CARD16],
      ['numberOfScreens', Type::CARD8],
      ['numberOfFormats', Type::CARD8],
      ['imageByteOrder', Type::ENUM8, ['LSBFirst', 'MSBFirst']],
      ['bitmapFormatBitOrder', Type::ENUM8, ['LeastSignificant', 'MostSignificant']],
      ['bitmapFormatScanlineUnit', Type::CARD8],
      ['bitmapFormatScanlinePad', Type::CARD8],
      ['minKeycode', Type::CARD8],
      ['maxKeycode', Type::CARD8],
      ['unused', Type::CARD32]
    ]);
    $vendor = self::receiveResponse([
      ['vendor', Type::STRING8, $response['lengthOfVendor']]
    ]);
    $formats = [];
    for ($i = 0; $i < $response['numberOfFormats']; $i++) {
      $formats[] = self::receiveResponse([
        ['depth', Type::CARD8],
        ['bitsPerPixel', Type::CARD8],
        ['scanlinePad', Type::CARD8],
        ['unused', Type::BYTE],
        ['unused', Type::BYTE],
        ['unused', Type::BYTE],
        ['unused', Type::BYTE],
        ['unused', Type::BYTE],
      ]);
    }
    $screens = [];
    for ($i = 0; $i < $response['numberOfScreens']; $i++) {
      $screen = self::receiveResponse([
        ['root', Type::ID],
        ['defaultColormap', Type::ID],
        ['whitePixel', Type::CARD32],
        ['blackPixel', Type::CARD32],
        ['currentInputMasks', Type::CARD32],
        ['widthInPixels', Type::CARD16],
        ['heightInPixels', Type::CARD16],
        ['widthInMilimeters', Type::CARD16],
        ['heightInMilimeters', Type::CARD16],
        ['minInstalledMaps', Type::CARD16],
        ['maxInstalledMaps', Type::CARD16],
        ['rootVisual', Type::ID],
        ['backingStores', Type::ENUM8, ['Never', 'WhenMapped', 'Always']],
        ['saveUnders', Type::BOOL],
        ['rootDepth', Type::CARD8],
        ['numberOfDepths', Type::CARD8]
      ]);
      $depths = [];
      for ($j = 0; $j < $screen['numberOfDepths']; $j++) {
        $depth = self::receiveResponse([
          ['depth', Type::CARD8],
          ['unused', Type::BYTE],
          ['numberOfVisualTypes', Type::CARD16],
          ['unused', Type::CARD32]
        ]);
        $visualsTypes = [];
        for ($k = 0; $k < $depth['numberOfVisualTypes']; $k++) {
          $visualTypes[] = self::receiveResponse([
            ['visualId', Type::ID],
            ['class', Type::ENUM8, ['StaticGray', 'GrayScale', 'StaticColor', 'PseudoColor', 'TrueColor', 'DirectColor']],
            ['bitsPerRgbValue', Type::CARD8],
            ['colormapEntries', Type::CARD16],
            ['redMask', Type::CARD32],
            ['greenMask', Type::CARD32],
            ['blueMask', Type::CARD32],
            ['unused', Type::CARD32],
          ]);
        }
        $depth['visualTypes'] = $visualTypes;
        $depths[] = $depth;
      }
      $screen['depths'] = $depth;
      $screens[] = $screen;
    }
    $response['screens'] = $screens;
    return $response;
  }

  protected static function ConnectionAuthenticate() {
    echo "Authentication required\n";
    $response = self::receiveResponse([
      ['unused', Type::BYTE],
      ['unused', Type::BYTE],
      ['unused', Type::BYTE],
      ['unused', Type::BYTE],
      ['unused', Type::BYTE],
      ['length', Type::CARD16],
    ]);
    $reason = self::receiveResponse([
      ['reason', Type::STRING8, $response['length'] << 2]
    ]);
    throw new \Exception("Authentication required: {$reason}");
  }

  public static function ConnectionInit() {
    $status = self::receiveResponse([
      ['status', Type::ENUM8, ['Failed', 'Success', 'Authenticate']]
    ]);
    switch ($status) {
      case 'Failed':
        return self::ConnectionFailed();
      case 'Success':
        return self::ConnectionSuccess();
      case 'Authenticate':
        return self::ConnectionAuthenticate();
    }
  }

  public static function GetWindowAttributes() {
    $response = self::receiveResponse([
      ['reply', Type::BYTE],
      ['backingStore', Type::ENUM8, ['NotUseful', 'WhenMapped', 'Always']],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['visualId', Type::ID],
      ['class', Type::ENUM16, ['InputOutput', 'InputOnly']],
      ['bitGravity', Type::BYTE],
      ['winGravity', Type::BYTE],
      ['backingPlanes', Type::CARD32],
      ['backingPixel', Type::CARD32],
      ['saveUnder', Type::BOOL],
      ['mapIsInstalled', Type::BOOL],
      ['mapState', Type::ENUM8, ['Unmapped', 'Unviewable', 'Viewable']],
      ['overrideRedirect', Type::BOOL],
      ['colormap', Type::ID],
      ['allEventMasks', Type::CARD32],
      ['yourEventMask', Type::CARD32],
      ['doNotPropagateMask', Type::CARD16],
      ['unused', Type::CARD16]
    ]);
    return $response;
  }

/*

  public static function GetGeometry() {
▶
     1     1                               Reply
     1     CARD8                           depth
     2     CARD16                          sequence number
     4     0                               reply length
     4     WINDOW                          root
     2     INT16                           x
     2     INT16                           y
     2     CARD16                          width
     2     CARD16                          height
     2     CARD16                          border-width
     10                                    unused
  }

  public static function QueryTree() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     n                               reply length
     4     WINDOW                          root
     4     WINDOW                          parent
          0     None
     2     n                               number of WINDOWs in children
     14                                    unused
     4n     LISTofWINDOW                   children
  }

  public static function InternAtom() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     0                               reply length
     4     ATOM                            atom
           0     None
     20                                    unused
  }

  public static function GetProperty() {
▶
     1     1                               Reply
     1     CARD8                           format
     2     CARD16                          sequence number
     4     (n+p)/4                         reply length
     4     ATOM                            type
          0     None
     4     CARD32                          bytes-after
     4     CARD32                          length of value in format units
                    (= 0 for format = 0)
                    (= n for format = 8)
                    (= n/2 for format = 16)
                    (= n/4 for format = 32)
     12                                    unused
     n     LISTofBYTE                      value
                    (n is zero for format = 0)
                    (n is a multiple of 2 for format = 16)
                    (n is a multiple of 4 for format = 32)
     p                                     unused, p=pad(n)
  }

  public static function ListProperty() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     n                               reply length
     2     n                               number of ATOMs in atoms
     22                                    unused
     4n     LISTofATOM                     atoms
  }

  public static function GetSelectionOwner() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     0                               reply length
     4     WINDOW                          owner
          0     None
     20                                    unused
  }

  public static function GrabPointer() {
▶
     1     1                               Reply
     1                                     status
          0     Success
          1     AlreadyGrabbed
          2     InvalidTime
          3     NotViewable
          4     Frozen
     2     CARD16                          sequence number
     4     0                               reply length
     24                                    unused
  }

  public static function GrabKeyboard() {
▶
     1     1                               Reply
     1                                     status
          0     Success
          1     AlreadyGrabbed
          2     InvalidTime
          3     NotViewable
          4     Frozen
     2     CARD16                          sequence number
     4     0                               reply length
     24                                    unused
  }

  public static function QueryPointer() {
▶
     1     1                               Reply
     1     BOOL                            same-screen
     2     CARD16                          sequence number
     4     0                               reply length
     4     WINDOW                          root
     4     WINDOW                          child
          0     None
     2     INT16                           root-x
     2     INT16                           root-y
     2     INT16                           win-x
     2     INT16                           win-y
     2     SETofKEYBUTMASK                 mask
     6                                     unused
  }

  public static function GetMotionEvents() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     2n                              reply length
     4     n                               number of TIMECOORDs in events
     20                                    unused
     8n     LISTofTIMECOORD                events

  TIMECOORD
     4     TIMESTAMP                       time
     2     INT16                           x
     2     INT16                           y
  }

  public static function TranslateCoordinates() {
▶
     1     1                               Reply
     1     BOOL                            same-screen
     2     CARD16                          sequence number
     4     0                               reply length
     4     WINDOW                          child
          0     None
     2     INT16                           dst-x
     2     INT16                           dst-y
     16                                    unused


  }

  public static function GetInputFocus() {
▶
     1     1                               Reply
     1                                     revert-to
          0     None
          1     PointerRoot
          2     Parent
     2     CARD16                          sequence number
     4     0                               reply length
     4     WINDOW                          focus
          0     None
          1     PointerRoot
     20                                    unused
  }

  public static function QueryKeyMap() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     2                               reply length
     32     LISTofCARD8                    keys
  }

  public static function QueryFont() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     7+2n+3m                         reply length
     12     CHARINFO                       min-bounds
     4                                     unused
     12     CHARINFO                       max-bounds
     4                                     unused
     2     CARD16                          min-char-or-byte2
     2     CARD16                          max-char-or-byte2
     2     CARD16                          default-char
     2     n                               number of FONTPROPs in properties
     1                                     draw-direction
          0     LeftToRight
          1     RightToLeft
     1     CARD8                           min-byte1
     1     CARD8                           max-byte1
     1     BOOL                            all-chars-exist
     2     INT16                           font-ascent
     2     INT16                           font-descent
     4     m                               number of CHARINFOs in char-infos
     8n     LISTofFONTPROP                 properties
     12m     LISTofCHARINFO                char-infos

  FONTPROP
     4     ATOM                            name
     4     <32-bits>                 value

  CHARINFO
     2     INT16                           left-side-bearing
     2     INT16                           right-side-bearing
     2     INT16                           character-width
     2     INT16                           ascent
     2     INT16                           descent
     2     CARD16                          attributes

  }

  public static funciton QueryTextExtents() {
▶
     1     1                               Reply
     1                                     draw-direction
          0     LeftToRight
          1     RightToLeft
     2     CARD16                          sequence number
     4     0                               reply length
     2     INT16                           font-ascent
     2     INT16                           font-descent
     2     INT16                           overall-ascent
     2     INT16                           overall-descent
     4     INT32                           overall-width
     4     INT32                           overall-left
     4     INT32                           overall-right
     4                                     unused
  }

  public static function ListFonts() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     (n+p)/4                         reply length
     2     CARD16                          number of STRs in names
     22                                    unused
     n     LISTofSTR                       names
     p                                     unused, p=pad(n)
  }

  public static function ListFontsWithInfo() {
▶ (except for last in series)
     1     1                               Reply
     1     n                               length of name in bytes
     2     CARD16                          sequence number
     4     7+2m+(n+p)/4                    reply length
     12     CHARINFO                       min-bounds
     4                                     unused
     12     CHARINFO                       max-bounds
     4                                     unused
     2     CARD16                          min-char-or-byte2
     2     CARD16                          max-char-or-byte2
     2     CARD16                          default-char
     2     m                               number of FONTPROPs in properties
     1                                     draw-direction
          0     LeftToRight
          1     RightToLeft
     1     CARD8                           min-byte1
     1     CARD8                           max-byte1
     1     BOOL                            all-chars-exist
     2     INT16                           font-ascent
     2     INT16                           font-descent
     4     CARD32                          replies-hint
     8m     LISTofFONTPROP                 properties
     n     STRING8                         name
     p                                     unused, p=pad(n)

  FONTPROP
     encodings are the same as for QueryFont

  CHARINFO
     encodings are the same as for QueryFont

▶ (last in series)
     1     1                               Reply
     1     0                               last-reply indicator
     2     CARD16                          sequence number
     4     7                               reply length
     52                                    unused
  }

  public static function GetFonthPath() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     (n+p)/4                         reply length
     2     CARD16                          number of STRs in path
     22                                    unused
     n     LISTofSTR                       path
     p                                     unused, p=pad(n)
  }

  public static function GetImage() {
▶
     1     1                               Reply
     1     CARD8                           depth
     2     CARD16                          sequence number
     4     (n+p)/4                         reply length
     4     VISUALID                        visual
          0     None
     20                                    unused
     n     LISTofBYTE                      data
     p                                     unused, p=pad(n)
  }

  public static function ListInstalledColormap() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     n                               reply length
     2     n                               number of COLORMAPs in cmaps
     22                                    unused
     4n     LISTofCOLORMAP                 cmaps
  }

  public static function AllocColor() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     0                               reply length
     2     CARD16                          red
     2     CARD16                          green
     2     CARD16                          blue
     2                                     unused
     4     CARD32                          pixel
     12                                    unused
  }

  public static function AllocNamedColor() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     0                               reply length
     4     CARD32                          pixel
     2     CARD16                          exact-red
     2     CARD16                          exact-green
     2     CARD16                          exact-blue
     2     CARD16                          visual-red
     2     CARD16                          visual-green
     2     CARD16                          visual-blue
     8                                     unused
  }

  public static function AllocColorCells() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     n+m                             reply length
     2     n                               number of CARD32s in pixels
     2     m                               number of CARD32s in masks
     20                                    unused
     4n     LISTofCARD32                   pixels
     4m     LISTofCARD32                   masks
  }

  public static function AllocColorPlanes() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     n                               reply length
     2     n                               number of CARD32s in pixels
     2                                     unused
     4     CARD32                          red-mask
     4     CARD32                          green-mask
     4     CARD32                          blue-mask
     8                                     unused
     4n     LISTofCARD32                   pixels
  }

*/

}

