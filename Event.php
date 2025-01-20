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
      ['root', Type::ID],
      ['event', Type::ID],
      ['child', Type::ID],
      ['rootX', Type::INT16],
      ['rootY', Type::INT16],
      ['eventX', Type::INT16],
      ['eventY', Type::INT16],
      ['state', Type::CARD16],
      ['sameScreen', Type::BOOL],
      ['unused', Type::BYTE]
    ],
    [ // KeyReleas
      ['code', Type::BYTE],
      ['detail', Type::BYTE],
      ['sequenceNumber', Type::CARD16],
      ['time', Type::CARD32],
      ['root', Type::ID],
      ['event', Type::ID],
      ['child', Type::ID],
      ['rootX', Type::INT16],
      ['rootY', Type::INT16],
      ['eventX', Type::INT16],
      ['eventY', Type::INT16],
      ['state', Type::CARD16],
      ['sameScreen', Type::BOOL],
      ['unused', Type::BYTE]
    ],
    [ // ButtonPress
      ['code', Type::BYTE],
      ['detail', Type::BYTE],
      ['sequenceNumber', Type::CARD16],
      ['time', Type::CARD32],
      ['root', Type::ID],
      ['event', Type::ID],
      ['child', Type::ID],
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
      ['root', Type::ID],
      ['event', Type::ID],
      ['child', Type::ID],
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
      ['root', Type::ID],
      ['event', Type::ID],
      ['child', Type::ID],
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
      ['root', Type::ID],
      ['event', Type::ID],
      ['child', Type::ID],
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
      ['root', Type::ID],
      ['event', Type::ID],
      ['child', Type::ID],
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
      ['window', Type::ID],
      ['mode', Type::ENUM8, ['Normal', 'Grab', 'Ungrab', 'WhileGrabbed']]
    ],
    [ // FocusOut
      ['code', Type::BYTE],
      ['detail', Type::ENUM8, ['Ancestor', 'Virtual', 'Inferior', 'Nonlinear', 'NonlinearVirtual', 'Pointer', 'PointerRoot', 'None']],
      ['sequenceNumber', Type::CARD16],
      ['window', Type::ID],
      ['mode', Type::ENUM8, ['Normal', 'Grab', 'Ungrab', 'WhileGrabbed']]
    ],
    [ // KeymapNotify
      ['code', Type::BYTE],
      ['keycode', Type::LIST, Type::CARD8, 31]
    ],
    [ // Expose
      ['code', Type::BYTE],
      ['unused', 1],
      ['sequenceNumber', Type::CARD16],
      ['window', Type::ID],
      ['x', Type::CARD16],
      ['y', Type::CARD16],
      ['width', Type::CARD16],
      ['height', Type::CARD16],
      ['count', Type::CARD16]
    ],
    [ // GraphicsExpose
      ['code', Type::BYTE],
      ['unused', 1],
      ['sequenceNumber', Type::CARD16],
      ['drawable', Type::ID],
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
      ['unused', 1],
      ['sequenceNumber', Type::CARD16],
      ['drawable', Type::ID],
      ['minorOpcode', Type::CARD16],
      ['majorOpcode', Type::CARD8]
    ],
    [ // VisibilityNotify
      ['code', Type::BYTE],
      ['unused', 1],
      ['sequenceNumber', Type::CARD16],
      ['window', Type::ID],
      ['stat', Type::ENUM8, ['Unobscured', 'PartiallyObscured', 'FullyObscured']]
    ],
    [ // CreateNotify
      ['code', Type::BYTE],
      ['unused', 1],
      ['sequenceNumber', Type::CARD16],
      ['parent', Type::ID],
      ['window', Type::ID],
      ['x', Type::INT16],
      ['y', Type::INT16],
      ['width', Type::CARD16],
      ['height', Type::CARD16],
      ['borderWidth', Type::CARD16],
      ['overrideRedirect', Type::BOOL]
    ],
    [ // DestroyNotify
      ['code', Type::BYTE],
      ['unused', 1],
      ['sequenceNumber', Type::CARD16],
      ['event', Type::ID],
      ['window', Type::ID]
    ],
    [ // UnmapNotify
      ['code', Type::BYTE],
      ['unused', 1],
      ['sequenceNumber', Type::CARD16],
      ['event', Type::ID],
      ['window', Type::ID],
      ['fromConfigure', Type::BOOL]
    ],
    [ // MapNotify
      ['code', Type::BYTE],
      ['unused', 1],
      ['sequenceNumber', Type::CARD16],
      ['event', Type::ID],
      ['window', Type::ID],
      ['fromConfigure', Type::BOOL]
    ],
    [ // MapRequest
      ['code', Type::BYTE],
      ['unused', 1],
      ['sequenceNumber', Type::CARD16],
      ['parent', Type::ID],
      ['window', Type::ID]
    ],
    [ // ReparentNotify
      ['code', Type::BYTE],
      ['unused', 1],
      ['sequenceNumber', Type::CARD16],
      ['event', Type::ID],
      ['window', Type::ID],
      ['parent', Type::ID],
      ['x', Type::INT16],
      ['y', Type::INT16],
      ['overrideRedirect', Type::BOOL]
    ],
    [ // ConfigureNotify
      ['code', Type::BYTE],
      ['unused', 1],
      ['sequenceNumber', Type::CARD16],
      ['event', Type::ID],
      ['window', Type::ID],
      ['aboveSibling', Type::ID],
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
      ['parent', Type::ID],
      ['window', Type::ID],
      ['sibling', Type::ID],
      ['x', Type::INT16],
      ['y', Type::INT16],
      ['width', Type::CARD16],
      ['height', Type::CARD16],
      ['borderWidth', Type::CARD16],
      ['valueMask', Type::BYTE]
    ],
    [ // GravityNotify
      ['code', Type::BYTE],
      ['unused', 1],
      ['sequenceNumber', Type::CARD16],
      ['event', Type::ID],
      ['window', Type::ID],
      ['x', Type::INT16],
      ['y', Type::INT16]
    ],
    [ // ResizeRequest
      ['code', Type::BYTE],
      ['unused', 1],
      ['sequenceNumber', Type::CARD16],
      ['window', Type::ID],
      ['width', Type::CARD16],
      ['height', Type::CARD16]
    ],
    [ // CirculateNotify
      ['code', Type::BYTE],
      ['unused', 1],
      ['sequenceNumber', Type::CARD16],
      ['event', Type::ID],
      ['window', Type::ID],
      ['unused', Type::ID],
      ['place', Type::ENUM8, ['Top', 'Bottom']]
    ],
    [ // CirculateRequest
      ['code', Type::BYTE],
      ['unused', 1],
      ['sequenceNumber', Type::CARD16],
      ['parent', Type::ID],
      ['window', Type::ID],
      ['unused', Type::ID],
      ['place', Type::ENUM8, ['Top', 'Bottom']]
    ],
    [ // PropertyNotify
      ['code', Type::BYTE],
      ['unused', 1],
      ['sequenceNumber', Type::CARD16],
      ['window', Type::ID],
      ['atom', Type::ID],
      ['time', Type::CARD32],
      ['state', Type::ENUM8, ['NewValue', 'Deleted']]
    ],
    [ // SelectionClear
      ['code', Type::BYTE],
      ['unused', 1],
      ['sequenceNumber', Type::CARD16],
      ['time', Type::CARD32],
      ['window', Type::ID],
      ['atom', Type::ID]
    ],
    [ // SelectionRequest
      ['code', Type::BYTE],
      ['unused', 1],
      ['sequenceNumber', Type::CARD16],
      ['time', Type::CARD32],
      ['owner', Type::ID],
      ['requestor', Type::ID],
      ['selection', Type::ID],
      ['target', Type::ID],
      ['property', Type::ID]
    ],
    [ // SelectionNotify
      ['code', Type::BYTE],
      ['unused', 1],
      ['sequenceNumber', Type::CARD16],
      ['time', Type::CARD32],
      ['requestor', Type::ID],
      ['selection', Type::ID],
      ['target', Type::ID],
      ['property', Type::ID]
    ],
    [ // ColormapNotify
      ['code', Type::BYTE],
      ['unused', 1],
      ['sequenceNumber', Type::CARD16],
      ['window', Type::ID],
      ['colormap', Type::ID],
      ['new', Type::BOOL],
      ['state', Type::ENUM8, ['Uninstalled', 'Installed']]
    ],
    [ // ColormapNotify
      ['code', Type::BYTE],
      ['unused', 1],
      ['sequenceNumber', Type::CARD16],
      ['window', Type::ID],
      ['type', Type::ID],
      ['data', [Type::LIST, Type::BYTE, 20]]
    ],
    [ // MappingNotify
      ['code', Type::BYTE],
      ['unused', 1],
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

  protected static function debug($event, $name) {
    echo "\n", str_pad("[ {$name} ]", 120, '-', STR_PAD_BOTH), "\n";
    foreach ($event as $name => $value) {
      echo '*  ', $name, ': ', $value, "\n";
    }
    echo "\n";
  }

  public static function bytesToArray($bytes) {
    $type = unpack('C', $bytes);
    $type = $type[1];
    if (!isset(self::$names[$type])) {
      echo "Unknown event\n";
      return false;
    }
    $name = self::$names[$type];
    if ($name == 'Reply') {
      throw new \Exception("Unreceived response detected.");
    }
    if ($name == 'Error') {
      Error::handle($bytes);
      return;
    }
    $format = [];
    $definition = self::$definitions[$type];
    foreach ($definition as $field) {
      $format[] = Type::$format[$field[1]] . $field[0];
    }
    $format = implode('/', $format);
    $event = unpack($format, $bytes);
    unset($event['unused']);
    $event['name'] = $name;
    if (DEBUG) {
      self::debug($event, $name);
    }
    return $event;
  }

  public static function loop($eventHandler) {
    while (!self::$end) {
      $bytes = Connection::read(32);
      $event = self::bytesToArray($bytes);
      call_user_func($eventHandler, $event);
    }
  }

  public static function end() {
    self::$end = true;
  }

}
