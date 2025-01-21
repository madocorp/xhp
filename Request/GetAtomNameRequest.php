<?php

namespace X11;

class GetAtomNameRequest extends Request {

  public function __construct($atom) {
    $this->sendRequest([
      ['opcode', 17, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['atom', $atom, Type::ATOM],
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}
