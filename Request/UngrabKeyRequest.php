<?php

namespace X11;

class UngrabKeyRequest extends Request {

  public function __construct($key, $grabWindow, $modifiers) {
    $opcode = 34;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['key', Type::BYTE],
      ['requestLength', Type::CARD16],
      ['grabWindow', Type::WINDOW],
      ['modifiers', Type::CARD16],
      ['unused', Type::UNUSED, 2]
    ], $values);
  }

}
