<?php

namespace X11;

class ChangeActivePointerGrabRequest extends Request {

  public function __construct($cursor, $timestamp, $eventMask) {
    $opcode = 30;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['cursor', Type::CURSOR],
      ['timestamp', Type::CARD32],
      ['eventMask', Type::BITMASK16, [false, false, 'ButtonPress', 'ButtonRelease', 'EnterWindow', 'LeaveWindow', 'PointerMotion', 'PointerMotionHint', 'Button1Motion', 'Button2Motion', 'Button3Motion', 'Button4Motion', 'Button5Motion', 'ButtonMotion', 'KeymapState']],
      ['unused', Type::UNUSED, 2]
    ], $values);
  }

}

