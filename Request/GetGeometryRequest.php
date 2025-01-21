<?php

namespace X11;

class GetGeometryRequest extends Request {

  public function __construct($drawable) {
    $this->doRequest([
      ['opcode', 14, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}
