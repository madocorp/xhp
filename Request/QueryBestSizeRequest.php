<?php

namespace X11;

class QueryBestSizeRequest extends Request {

  public function __construct($class, $drawable, $width, $height) {
    $this->sendRequest([
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

/*
  public static function QueryBestSize() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     0                               reply length
     2     CARD16                          width
     2     CARD16                          height
     20                                    unused
*/
