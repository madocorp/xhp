<?php

namespace X11;

class SendEventRequest extends Request {

  public function __construct($propagate, $destination, $eventMask, $event) {
    $this->sendRequest([
      ['opcode', 25, Type::BYTE],
      ['propagate', 0, Type::BOOL],
      ['requestLength', 3, Type::CARD16],
      ['destination', $destination, Type::WINDOW],
      ['eventMask', $eventMask, Type::BITMASK32, ['KeyPress', 'KeyRelease', 'ButtonPress', 'ButtonRelease', 'EnterWindow', 'LeaveWindow', 'PointerMotion', 'PointerMotionHint', 'Button1Motion', 'Button2Motion', 'Button3Motion', 'Button4Motion', 'Button5Motion', 'ButtonMotion', 'KeymapState', 'Exposure', 'VisibilityChange', 'StructureNotify', 'ResizeRedirect', 'SubstructureNotify', 'SubstructureRedirect', 'FocusChange', 'PropertyChange', 'ColormapChange', 'OwnerGrabButton']],
      ['event', $event, Type::STRING8, 32]
    ]);
  }

}
