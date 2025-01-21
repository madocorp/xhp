<?php

namespace X11;

class TranslateCoordinatesRequest extends Request {

  public function __construct($window, $srcX, $srcY) {
    $this->doRequest([
      ['opcode', 40, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4, Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['srcX', $srcX, Type::INT16],
      ['srcY', $srcY, Type::INT16]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}
