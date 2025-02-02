<?php

namespace X11;

class ForceScreenSaverRequest extends Request {

  public function __construct($mode) {
    $opcode = 115;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['mode', Type::ENUM8, ['Reset', 'Activate']],
      ['requestLength', Type::CARD16]
    ], $values);
  }

}
