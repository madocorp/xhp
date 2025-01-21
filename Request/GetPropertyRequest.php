<?php

namespace X11;

class GetPropertyRequest extends Request {

  public function __construct($delete, $window, $property, $type, $longOffset, $longLength) {
    $this->doRequest([
      ['opcode', 20, Type::BYTE],
      ['delete', $delete, Type::BOOL],
      ['requestLength', 6, Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['property', $property, Type::ATOM],
      ['type', $type, Type::ATOM],
      ['longOffset', $longOffset, Type::CARD32],
      ['longLength', $longLength, Type::CARD32]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}
