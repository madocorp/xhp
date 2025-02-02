<?php

namespace X11;

class SetCloseDownModeRequest extends Request {

  public function __construct($mode) {
    $opcode = 112;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['mode', Type::ENUM8, ['Destroy', 'RetainPermanent', 'RetainTemporary']],
      ['requestLength', Type::CARD16]
    ], $values);
  }

}
