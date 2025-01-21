<?php

namespace X11;

class QueryTreeRequest extends Request {

  public function __construct($window) {
    $this->sendRequest([
      ['opcode', 15, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['window', $window, Type::WINDOW]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}

/*
  public static function QueryTree() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     n                               reply length
     4     WINDOW                          root
     4     WINDOW                          parent
          0     None
     2     n                               number of WINDOWs in children
     14                                    unused
     4n     LISTofWINDOW                   children
*/
