<?php

namespace X11;

class GrabServerRequest extends Request {

  public function __construct() {
    $this->doRequest([
      ['opcode', 36, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 1, Type::CARD16]
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
