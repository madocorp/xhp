<?php

namespace X11;

class GetPointerMappingRequest extends Request {

  public function __construct() {
    $this->sendRequest([
      ['opcode', 117, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 1, Type::CARD16]
    ]);
    Connection::setResponse($this->processResponse());
  }


  protected function processResponse() {
    return false;
  }

}

/*
  public static function GetPointerMapping() {
▶
     1     1                               Reply
     1     n                               length of map
     2     CARD16                          sequence number
     4     (n+p)/4                         reply length
     24                                    unused
     n     LISTofCARD8                     map
     p                                     unused, p=pad(n)
*/
