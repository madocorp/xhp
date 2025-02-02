<?php

namespace X11;

class UnmapWindowRequest extends Request {

  public function __construct($window) {
    $opcode = 10;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['window', Type::WINDOW]
    ], $values);
  }

}
