<?php

namespace X11;

class GetFontPathRequest extends Request {

  public function __construct() {
    $this->doRequest([
      ['opcode', 52, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}
