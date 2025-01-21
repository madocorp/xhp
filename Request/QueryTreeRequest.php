<?php

namespace X11;

class QueryTreeRequest extends Request {

  public function __construct($window) {
    $this->doRequest([
      ['opcode', 15, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['window', $window, Type::WINDOW]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}
