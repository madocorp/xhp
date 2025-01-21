<?php

namespace X11;

class GetPointerControlRequest extends Request {

  public function __construct() {
    $this->sendRequest([
      ['opcode', 106, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 1, Type::CARD16],
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}

/*
  public static function GetPointerControl() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     0                               reply length
     2     CARD16                          acceleration-numerator
     2     CARD16                          acceleration-denominator
     2     CARD16                          threshold
     18                                    unused
*/
