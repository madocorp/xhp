<?php

namespace X11;

class SetAccessControlRequest extends Request {

  public function __construct($mode) {
    $opcode = 111;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['mode', Type::ENUM8, ['Disable', 'Enable']],
      ['requestLength', Type::CARD16]
    ], $values);
  }

}
