<?php

namespace X11;

class QueryPointerRequest extends Request {

  public function __construct($window) {
    $this->sendRequest([
      ['opcode', 38, Type::BYTE],
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
  public static function QueryPointer() {
▶
     1     1                               Reply
     1     BOOL                            same-screen
     2     CARD16                          sequence number
     4     0                               reply length
     4     WINDOW                          root
     4     WINDOW                          child
          0     None
     2     INT16                           root-x
     2     INT16                           root-y
     2     INT16                           win-x
     2     INT16                           win-y
     2     SETofKEYBUTMASK                 mask
     6                                     unused
*/
