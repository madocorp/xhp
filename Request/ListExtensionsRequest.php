<?php

namespace X11;

class ListExtensionsRequest extends Request {

  public function __construct() {
    $this->sendRequest([
      ['opcode', 99, Type::BYTE],
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
  public static function ListExtensions() {
▶
     1     1                               Reply
     1     CARD8                           number of STRs in names
     2     CARD16                          sequence number
     4     (n+p)/4                         reply length
     24                                    unused
     n     LISTofSTR                       names
     p                                     unused, p=pad(n)
*/
