<?php

namespace X11;

class GetGeometryRequest extends Request {

  public function __construct($drawable) {
    $this->sendRequest([
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

/*
  public static function GetGeometry() {
▶
     1     1                               Reply
     1     CARD8                           depth
     2     CARD16                          sequence number
     4     0                               reply length
     4     WINDOW                          root
     2     INT16                           x
     2     INT16                           y
     2     CARD16                          width
     2     CARD16                          height
     2     CARD16                          border-width
     10                                    unused
*/
