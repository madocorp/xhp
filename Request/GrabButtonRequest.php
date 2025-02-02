<?php

namespace X11;

class GrabButtonRequest extends Request {

  public function __construct($ownerEvents, $grabWindow, $eventMask, $pointerMode, $keyboardMode, $confineTo, $cursor, $button, $modifiers) {
    $opcode = 28;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['ownerEvents', Type::BOOL],
      ['requestLength', Type::CARD16],
      ['grabWindow', Type::WINDOW],
      ['eventMask', Type::BITMASK16, [false, false, 'ButtonPress', 'ButtonRelease', 'EnterWindow', 'LeaveWindow', 'PointerMotion', 'PointerMotionHint', 'Button1Motion', 'Button2Motion', 'Button3Motion', 'Button4Motion', 'Button5Motion', 'ButtonMotion', 'KeymapState']],
      ['pointerMode', Type::ENUM8, ['Synchronous', 'Asynchronous']],
      ['keyboardMode', Type::ENUM8, ['Synchronous', 'Asynchronous']],
      ['confineTo', Type::WINDOW],
      ['cursor', Type::CURSOR],
      ['button', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['modifiers', Type::CARD16]
    ], $values);
  }

}
