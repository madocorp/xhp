<?php

namespace X11;

class GrabKeyRequest extends Request {

  public function __construct($ownerEvents, $grabWindow, $modifiers, $key, $pointerMode, $keyboardMode) {
    $opcode = 33;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['ownerEvents', Type::BOOL],
      ['requestLength', Type::CARD16],
      ['grabWindow', Type::WINDOW],
      ['modifiers', Type::CARD16],
      ['key', Type::BYTE],
      ['pointerMode', Type::ENUM8, ['Synchronous', 'Asynchronous']],
      ['keyboardMode', Type::ENUM8, ['Synchronous', 'Asynchronous']],
      ['unused', Type::UNUSED, 3]
    ], $values);
  }

}
