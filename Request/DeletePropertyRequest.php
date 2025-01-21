<?php

namespace X11;

class DeletePropertyRequest extends Request {

  public function __construct($window, $property) {
    $this->doRequest([
      ['opcode', 19, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['property', $property, Type::ATOM]
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
