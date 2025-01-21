<?php

namespace X11;

class QueryBestSizeRequest extends Request {

  public function __construct($class, $drawable, $width, $height) {
    $this->doRequest([
      ['opcode', 97, Type::BYTE],
      ['class', $class, Type::ENUM8, ['Cursor', 'Tile', 'Stipple']],
      ['requestLength', 3, Type::CARD16],
      ['drawable', $drawable, Type::CURSOR],
      ['width', $width, Type::CARD16],
      ['height', $height, Type::CARD16]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}
