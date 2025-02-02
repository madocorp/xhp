<?php

namespace X11;

class UngrabKeyboardRequest extends Request {

  public function __construct($timestamp) {
    $opcode = 32;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['timestamp', Type::CARD32]
    ], $values);
  }

}
