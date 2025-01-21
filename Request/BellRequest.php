<?php

namespace X11;

class BellRequest extends Request {

  public function __construct($percent) {
    $this->doRequest([
      ['opcode', 104, Type::BYTE],
      ['percent', $percent, Type::INT8],
      ['requestLength', 1, Type::CARD16],
    ]);
  }



  protected function processResponse() {
    return false;
  }

}
