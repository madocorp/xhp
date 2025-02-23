<?php

namespace X11;

class Event {

  protected static $definitions = [
    [], // Error
    [], // Reply
    [ // KeyPress
      ['code', Type::BYTE],
      ['detail', Type::BYTE],
      ['sequenceNumber', Type::CARD16],
      ['time', Type::CARD32],
      ['root', Type::WINDOW],
      ['event', Type::WINDOW],
      ['child', Type::WINDOW],
      ['rootX', Type::INT16],
      ['rootY', Type::INT16],
      ['eventX', Type::INT16],
      ['eventY', Type::INT16],
      ['state', Type::CARD16],
      ['sameScreen', Type::BOOL],
      ['unused', Type::BYTE, 1]
    ],
    [ // KeyReleas
      ['code', Type::BYTE],
      ['detail', Type::BYTE],
      ['sequenceNumber', Type::CARD16],
      ['time', Type::CARD32],
      ['root', Type::WINDOW],
      ['event', Type::WINDOW],
      ['child', Type::WINDOW],
      ['rootX', Type::INT16],
      ['rootY', Type::INT16],
      ['eventX', Type::INT16],
      ['eventY', Type::INT16],
      ['state', Type::CARD16],
      ['sameScreen', Type::BOOL],
      ['unused', Type::BYTE, 1]
    ],
    [ // ButtonPress
      ['code', Type::BYTE],
      ['detail', Type::BYTE],
      ['sequenceNumber', Type::CARD16],
      ['time', Type::CARD32],
      ['root', Type::WINDOW],
      ['event', Type::WINDOW],
      ['child', Type::WINDOW],
      ['rootX', Type::INT16],
      ['rootY', Type::INT16],
      ['eventX', Type::INT16],
      ['eventY', Type::INT16],
      ['state', Type::CARD16],
      ['sameScreen', Type::BOOL]
    ],
    [ // ButtonRelease
      ['code', Type::BYTE],
      ['detail', Type::BYTE],
      ['sequenceNumber', Type::CARD16],
      ['time', Type::CARD32],
      ['root', Type::WINDOW],
      ['event', Type::WINDOW],
      ['child', Type::WINDOW],
      ['rootX', Type::INT16],
      ['rootY', Type::INT16],
      ['eventX', Type::INT16],
      ['eventY', Type::INT16],
      ['state', Type::CARD16],
      ['sameScreen', Type::BOOL]
    ],
    [ // MotionNotify
      ['code', Type::BYTE],
      ['detail', Type::ENUM8, ['Normal', 'Hint']],
      ['sequenceNumber', Type::CARD16],
      ['time', Type::CARD32],
      ['root', Type::WINDOW],
      ['event', Type::WINDOW],
      ['child', Type::WINDOW],
      ['rootX', Type::INT16],
      ['rootY', Type::INT16],
      ['eventX', Type::INT16],
      ['eventY', Type::INT16],
      ['state', Type::CARD16],
      ['sameScreen', Type::BOOL]
    ],
    [ // EnterNotify
      ['code', Type::BYTE],
      ['detail', Type::ENUM8, ['Ancestor', 'Virtual', 'Inferior', 'Nonlinear', 'NonlinearVirtual']],
      ['sequenceNumber', Type::CARD16],
      ['time', Type::CARD32],
      ['root', Type::WINDOW],
      ['event', Type::WINDOW],
      ['child', Type::WINDOW],
      ['rootX', Type::INT16],
      ['rootY', Type::INT16],
      ['eventX', Type::INT16],
      ['eventY', Type::INT16],
      ['state', Type::CARD16],
      ['mode', Type::ENUM8, ['Normal', 'Grab', 'Ungrab']],
      ['sameScreenAndFocus', Type::BYTE]
    ],
    [ // LeaveNotify
      ['code', Type::BYTE],
      ['detail', Type::ENUM8, ['Ancestor', 'Virtual', 'Inferior', 'Nonlinear', 'NonlinearVirtual']],
      ['sequenceNumber', Type::CARD16],
      ['time', Type::CARD32],
      ['root', Type::WINDOW],
      ['event', Type::WINDOW],
      ['child', Type::WINDOW],
      ['rootX', Type::INT16],
      ['rootY', Type::INT16],
      ['eventX', Type::INT16],
      ['eventY', Type::INT16],
      ['state', Type::CARD16],
      ['mode', Type::ENUM8, ['Normal', 'Grab', 'Ungrab']],
      ['sameScreenAndFocus', Type::BYTE]
    ],
    [ // FocusIn
      ['code', Type::BYTE],
      ['detail', Type::ENUM8, ['Ancestor', 'Virtual', 'Inferior', 'Nonlinear', 'NonlinearVirtual', 'Pointer', 'PointerRoot', 'None']],
      ['sequenceNumber', Type::CARD16],
      ['window', Type::WINDOW],
      ['mode', Type::ENUM8, ['Normal', 'Grab', 'Ungrab', 'WhileGrabbed']]
    ],
    [ // FocusOut
      ['code', Type::BYTE],
      ['detail', Type::ENUM8, ['Ancestor', 'Virtual', 'Inferior', 'Nonlinear', 'NonlinearVirtual', 'Pointer', 'PointerRoot', 'None']],
      ['sequenceNumber', Type::CARD16],
      ['window', Type::WINDOW],
      ['mode', Type::ENUM8, ['Normal', 'Grab', 'Ungrab', 'WhileGrabbed']]
    ],
    [ // KeymapNotify
      ['code', Type::BYTE],
      ['keycode', Type::VLIST, Type::CARD8, 31]
    ],
    [ // Expose
      ['code', Type::BYTE],
      ['unused', Type::BYTE, 1],
      ['sequenceNumber', Type::CARD16],
      ['window', Type::WINDOW],
      ['x', Type::CARD16],
      ['y', Type::CARD16],
      ['width', Type::CARD16],
      ['height', Type::CARD16],
      ['count', Type::CARD16]
    ],
    [ // GraphicsExpose
      ['code', Type::BYTE],
      ['unused', Type::BYTE, 1],
      ['sequenceNumber', Type::CARD16],
      ['drawable', Type::DRAWABLE],
      ['x', Type::CARD16],
      ['y', Type::CARD16],
      ['width', Type::CARD16],
      ['height', Type::CARD16],
      ['minorOpcode', Type::CARD16],
      ['count', Type::CARD16],
      ['majorOpcode', Type::CARD8]
    ],
    [ // NoExposure
      ['code', Type::BYTE],
      ['unused', Type::BYTE, 1],
      ['sequenceNumber', Type::CARD16],
      ['drawable', Type::DRAWABLE],
      ['minorOpcode', Type::CARD16],
      ['majorOpcode', Type::CARD8]
    ],
    [ // VisibilityNotify
      ['code', Type::BYTE],
      ['unused', Type::BYTE, 1],
      ['sequenceNumber', Type::CARD16],
      ['window', Type::WINDOW],
      ['stat', Type::ENUM8, ['Unobscured', 'PartiallyObscured', 'FullyObscured']]
    ],
    [ // CreateNotify
      ['code', Type::BYTE],
      ['unused', Type::BYTE, 1],
      ['sequenceNumber', Type::CARD16],
      ['parent', Type::WINDOW],
      ['window', Type::WINDOW],
      ['x', Type::INT16],
      ['y', Type::INT16],
      ['width', Type::CARD16],
      ['height', Type::CARD16],
      ['borderWidth', Type::CARD16],
      ['overrideRedirect', Type::BOOL]
    ],
    [ // DestroyNotify
      ['code', Type::BYTE],
      ['unused', Type::BYTE, 1],
      ['sequenceNumber', Type::CARD16],
      ['event', Type::WINDOW],
      ['window', Type::WINDOW]
    ],
    [ // UnmapNotify
      ['code', Type::BYTE],
      ['unused', Type::BYTE, 1],
      ['sequenceNumber', Type::CARD16],
      ['event', Type::WINDOW],
      ['window', Type::WINDOW],
      ['fromConfigure', Type::BOOL]
    ],
    [ // MapNotify
      ['code', Type::BYTE],
      ['unused', Type::BYTE, 1],
      ['sequenceNumber', Type::CARD16],
      ['event', Type::WINDOW],
      ['window', Type::WINDOW],
      ['fromConfigure', Type::BOOL]
    ],
    [ // MapRequest
      ['code', Type::BYTE],
      ['unused', Type::BYTE, 1],
      ['sequenceNumber', Type::CARD16],
      ['parent', Type::WINDOW],
      ['window', Type::WINDOW]
    ],
    [ // ReparentNotify
      ['code', Type::BYTE],
      ['unused', Type::BYTE, 1],
      ['sequenceNumber', Type::CARD16],
      ['event', Type::WINDOW],
      ['window', Type::WINDOW],
      ['parent', Type::WINDOW],
      ['x', Type::INT16],
      ['y', Type::INT16],
      ['overrideRedirect', Type::BOOL]
    ],
    [ // ConfigureNotify
      ['code', Type::BYTE],
      ['unused', Type::BYTE, 1],
      ['sequenceNumber', Type::CARD16],
      ['event', Type::WINDOW],
      ['window', Type::WINDOW],
      ['aboveSibling', Type::WINDOW],
      ['x', Type::INT16],
      ['y', Type::INT16],
      ['width', Type::CARD16],
      ['height', Type::CARD16],
      ['borderWidth', Type::CARD16],
      ['overrideRedirect', Type::BOOL]
    ],
    [ // ConfigureRequest
      ['code', Type::BYTE],
      ['stackMode', Type::ENUM8, ['Above', 'Below', 'TopIf', 'BottomIf', 'Opposite']],
      ['sequenceNumber', Type::CARD16],
      ['parent', Type::WINDOW],
      ['window', Type::WINDOW],
      ['sibling', Type::WINDOW],
      ['x', Type::INT16],
      ['y', Type::INT16],
      ['width', Type::CARD16],
      ['height', Type::CARD16],
      ['borderWidth', Type::CARD16],
      ['valueMask', Type::BYTE]
    ],
    [ // GravityNotify
      ['code', Type::BYTE],
      ['unused', Type::BYTE, 1],
      ['sequenceNumber', Type::CARD16],
      ['event', Type::WINDOW],
      ['window', Type::WINDOW],
      ['x', Type::INT16],
      ['y', Type::INT16]
    ],
    [ // ResizeRequest
      ['code', Type::BYTE],
      ['unused', Type::BYTE, 1],
      ['sequenceNumber', Type::CARD16],
      ['window', Type::WINDOW],
      ['width', Type::CARD16],
      ['height', Type::CARD16]
    ],
    [ // CirculateNotify
      ['code', Type::BYTE],
      ['unused', Type::BYTE, 1],
      ['sequenceNumber', Type::CARD16],
      ['event', Type::WINDOW],
      ['window', Type::WINDOW],
      ['unused', Type::CARD32],
      ['place', Type::ENUM8, ['Top', 'Bottom']]
    ],
    [ // CirculateRequest
      ['code', Type::BYTE],
      ['unused', Type::BYTE, 1],
      ['sequenceNumber', Type::CARD16],
      ['parent', Type::WINDOW],
      ['window', Type::WINDOW],
      ['unused', Type::CARD32],
      ['place', Type::ENUM8, ['Top', 'Bottom']]
    ],
    [ // PropertyNotify
      ['code', Type::BYTE],
      ['unused', Type::BYTE, 1],
      ['sequenceNumber', Type::CARD16],
      ['window', Type::WINDOW],
      ['atom', Type::ATOM],
      ['time', Type::CARD32],
      ['state', Type::ENUM8, ['NewValue', 'Deleted']]
    ],
    [ // SelectionClear
      ['code', Type::BYTE],
      ['unused', Type::BYTE, 1],
      ['sequenceNumber', Type::CARD16],
      ['time', Type::CARD32],
      ['window', Type::WINDOW],
      ['atom', Type::ATOM]
    ],
    [ // SelectionRequest
      ['code', Type::BYTE],
      ['unused', Type::BYTE, 1],
      ['sequenceNumber', Type::CARD16],
      ['time', Type::CARD32],
      ['owner', Type::WINDOW],
      ['requestor', Type::WINDOW],
      ['selection', Type::ATOM],
      ['target', Type::ATOM],
      ['property', Type::ATOM]
    ],
    [ // SelectionNotify
      ['code', Type::BYTE],
      ['unused', Type::BYTE, 1],
      ['sequenceNumber', Type::CARD16],
      ['time', Type::CARD32],
      ['requestor', Type::WINDOW],
      ['selection', Type::ATOM],
      ['target', Type::ATOM],
      ['property', Type::ATOM]
    ],
    [ // ColormapNotify
      ['code', Type::BYTE],
      ['unused', Type::BYTE, 1],
      ['sequenceNumber', Type::CARD16],
      ['window', Type::WINDOW],
      ['colormap', Type::COLORMAP],
      ['new', Type::BOOL],
      ['state', Type::ENUM8, ['Uninstalled', 'Installed']]
    ],
    [ // ClientMessage
      ['code', Type::BYTE],
      ['format', Type::CARD8, 1],
      ['sequenceNumber', Type::CARD16],
      ['window', Type::WINDOW],
      ['type', Type::ATOM],
      ['data', Type::VLIST, Type::BYTE, 20]
    ],
    [ // MappingNotify
      ['code', Type::BYTE],
      ['unused', Type::BYTE, 1],
      ['sequenceNumber', Type::CARD16],
      ['state', Type::ENUM8, ['Modifier', 'Keyboard', 'Pointer']],
      ['count', Type::CARD8]
    ]
  ];
  protected static $names = [
    'Error', 'Reply', 'KeyPress', 'KeyRelease',
    'ButtonPress', 'ButtonRelease', 'MotionNotify', 'EnterNotify',
    'LeaveNotify', 'FocusIn', 'FocusOut', 'KeymapNotify',
    'Expose', 'GraphicsExposure', 'NoExposure', 'VisibilityNotify',
    'CreateNotify', 'DestroyNotify', 'UnmapNotify', 'MapNotify',
    'MapRequest', 'ReparentNotify', 'ConfigureNotify', 'ConfigureRequest',
    'GravityNotify', 'ResizeRequest', 'CirculateNotify', 'CirculateRequest',
    'PropertyNotify', 'SelectionClear', 'SelectionRequest', 'SelectionNotify',
    'ColormapNotify', 'ClientMessage', 'MappingNotify'
  ];
  protected static $end = false;
  protected static $eventHandler = false;

  public static function arrayToBytes($name, $fields) {
    $code = array_search($name, self::$names);
    if ($code !== false) {
      $definition = self::$definitions[$code];
      $values = [];
      $formatString = '';
      $length = 0;
      foreach ($definition as $field) {
        if ($field[1] == Type::ENUM8) {
          $fields[$field[0]];
          $value = array_search($fields[$field[0]], $field[2]);
          $formatString .= 'C';
          $values[] = $value;
          $length++;
        } else if ($field[0] == 'code') {
          $formatString .= 'C';
          $values[] = $code;
          $length++;
        } else if ($field[0] == 'unused') {
          $formatString .= str_repeat('C', $field[1]);
          for ($i = 0; $i < $field[1]; $i++) {
            $values[] = 0;
          }
          $length += $field[1];
        } else {
          $formatString .= Type::$format[$field[1]];
          $values[] = $fields[$field[0]];
          $length += Type::$size[$field[1]];
        }
      }
      $n = 32 - $length;
      for ($i = 0; $i < $n; $i++) {
        $formatString .= 'C';
        $values[] = 0;
      }
      $bytes = pack($formatString, ...$values);
    } else {
      throw new \Exception('Unknown event.');
    }
    return $bytes;
  }

  public static function bytesToArray($bytes) {
    $eventType = unpack('C', $bytes);
    $eventType = $eventType[1];
    $sendEvent = (($eventType & 0x80) > 0);
    $eventType = $eventType & 0x7f;
    if (!isset(self::$names[$eventType])) {
      echo "Unknown event.\n";
      return false;
    }
    $eventName = self::$names[$eventType];
    if ($eventName == 'Reply') {
      throw new \Exception("Unreceived response detected.");
    }
    if ($eventName == 'Error') {
      Error::handle($bytes);
      return;
    }
    $format = [];
    $definition = self::$definitions[$eventType];
    foreach ($definition as $field) {
      $name = $field[0];
      $type = $field[1];
      if ($type == Type::VLIST) {
        $n = $field[3] * Type::$size[$field[2]];
        $format[] = 'a' . $n . $name;
      } else {
        $format[] = Type::$format[$type] . $name;
      }
    }
    $format = implode('/', $format);
    $event = unpack($format, $bytes);
    unset($event['unused']);
    $event['name'] = $eventName;
    $event['SendEvent'] = $sendEvent;
    if (DEBUG) {
      Debug::event($event, $eventName);
    }
    return $event;
  }

  public static function handle($bytes) {
    $event = self::bytesToArray($bytes);
    if (self::$eventHandler !== false) {
      call_user_func(self::$eventHandler, $event);
    }
  }

  public static function setHandler($eventHandler) {
    self::$eventHandler = $eventHandler;
  }

  public static function loop() {
    while (!self::$end) {
      $bytes = Connection::read(32);
      self::handle($bytes);
    }
  }

  public static function end() {
    self::$end = true;
  }

}
