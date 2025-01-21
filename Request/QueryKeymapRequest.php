<?php

namespace X11;

class QueryKeymapRequest extends Request {

  public function __construct() {
    $this->doRequest([
      ['opcode', 44, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 1, Type::CARD16]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}
