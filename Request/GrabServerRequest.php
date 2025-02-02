<?php

namespace X11;

class GrabServerRequest extends Request {

  public function __construct() {
    $opcode = 36;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16]
    ], $values);
  }

}
