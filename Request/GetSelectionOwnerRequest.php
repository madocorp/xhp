<?php

namespace X11;

class GetSelectionOwnerRequest extends Request {

  public function __construct($selection) {
    $this->sendRequest([
      ['opcode', 23, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['selection', $selection, Type::ATOM]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}

/*
  public static function GetSelectionOwner() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     0                               reply length
     4     WINDOW                          owner
          0     None
     20                                    unused
*/
