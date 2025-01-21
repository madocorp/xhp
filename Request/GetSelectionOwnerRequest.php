<?php

namespace X11;

class GetSelectionOwnerRequest extends Request {

  public function __construct($selection) {
    $this->doRequest([
      ['opcode', 23, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['selection', $selection, Type::ATOM]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}
