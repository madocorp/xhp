<?php

namespace X11;

class UngrabServerRequest extends Request {

  public function __construct() {
    $opcode = 37;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16]
    ], $values);
  }

}
