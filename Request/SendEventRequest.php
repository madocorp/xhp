<?php

namespace X11;

class SendEventRequest extends Request {

  public function __construct($propagate, $destination, $eventMask, $event) {
    $opcode = 25;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['propagate', Type::BOOL],
      ['requestLength', Type::CARD16],
      ['destination', Type::WINDOW],
      ['eventMask', Type::BITMASK32, ['KeyPress', 'KeyRelease', 'ButtonPress', 'ButtonRelease', 'EnterWindow', 'LeaveWindow', 'PointerMotion', 'PointerMotionHint', 'Button1Motion', 'Button2Motion', 'Button3Motion', 'Button4Motion', 'Button5Motion', 'ButtonMotion', 'KeymapState', 'Exposure', 'VisibilityChange', 'StructureNotify', 'ResizeRedirect', 'SubstructureNotify', 'SubstructureRedirect', 'FocusChange', 'PropertyChange', 'ColormapChange', 'OwnerGrabButton']],
      ['event', Type::STRING8, 32]
    ], $values);
  }

}
