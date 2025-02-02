<?php

namespace X11;

class BellRequest extends Request {

  public function __construct($percent) {
    $opcode = 104;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['percent', Type::INT8],
      ['requestLength', Type::CARD16]
    ], $values);
  }

}
