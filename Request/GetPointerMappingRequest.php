<?php

namespace X11;

class GetPointerMappingRequest extends Request {

  public function __construct() {
    $this->doRequest([
      ['opcode', 117, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 1, Type::CARD16]
    ]);
    Connection::setResponse($this->processResponse());
  }


  protected function processResponse() {
    return false;
  }

}
