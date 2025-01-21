<?php

namespace X11;

class ListHostsRequest extends Request {

  public function __construct() {
    $this->sendRequest([
      ['opcode', 110, Type::BYTE],
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
  public static function ListHosts() {
▶
     1     1                               Reply
     1                                     mode
          0     Disabled
          1     Enabled
     2     CARD16                          sequence number
     4     n/4                             reply length
     2     CARD16                          number of HOSTs in hosts
     22                                    unused
     n     LISTofHOST                      hosts (n always a multiple of 4)
*/
