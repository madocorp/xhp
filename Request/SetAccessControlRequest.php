<?php

namespace X11;

class SetAccessControlRequest extends Request {

  public function __construct($mode) {
    $this->doRequest([
      ['opcode', 111, Type::BYTE],
      ['mode', $mode, Type::ENUM8, ['Disable', 'Enable']],
      ['requestLength', 1, Type::CARD16]
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
