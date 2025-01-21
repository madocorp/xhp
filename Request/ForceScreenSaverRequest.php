<?php

namespace X11;

class ForceScreenSaverRequest extends Request {

  public function __construct($mode) {
    $this->doRequest([
      ['opcode', 115, Type::BYTE],
      ['mode', $mode, Type::ENUM8, ['Reset', 'Activate']],
      ['requestLength', 1, Type::CARD16],
    ]);
  }


  protected function processResponse() {
    return false;
  }

}
