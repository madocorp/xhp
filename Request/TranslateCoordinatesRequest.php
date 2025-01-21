<?php

namespace X11;

class TranslateCoordinatesRequest extends Request {

  public function __construct($window, $srcX, $srcY) {
    $this->sendRequest([
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

/*
  public static function TranslateCoordinates() {
▶
     1     1                               Reply
     1     BOOL                            same-screen
     2     CARD16                          sequence number
     4     0                               reply length
     4     WINDOW                          child
          0     None
     2     INT16                           dst-x
     2     INT16                           dst-y
     16                                    unused


*/
