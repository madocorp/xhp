<?php

namespace X11;

class ListPropertiesRequest extends Request {

  public function __construct($wid) {
    $this->doRequest([
      ['opcode', 21, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['property', $property, Type::ATOM]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}
