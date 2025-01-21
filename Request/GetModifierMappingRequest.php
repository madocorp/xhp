<?php

namespace X11;

class GetModifierMappingRequest extends Request {

  public function __construct() {
    $this->doRequest([
      ['opcode', 119, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 1, Type::CARD16]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}
