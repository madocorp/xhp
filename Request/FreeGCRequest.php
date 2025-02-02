<?php

namespace X11;

class FreeGCRequest extends Request {

  public function __construct($gc) {
    $opcode = 60;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['gc', Type::GCONTEXT]
    ], $values);
  }

}
