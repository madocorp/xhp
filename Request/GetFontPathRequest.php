<?php

namespace X11;

class GetFontPathRequest extends Request {

  public function __construct() {
    $this->sendRequest([
      ['opcode', 52, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}

/*
  public static function GetFontPath() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     (n+p)/4                         reply length
     2     CARD16                          number of STRs in path
     22                                    unused
     n     LISTofSTR                       path
     p                                     unused, p=pad(n)
*/
