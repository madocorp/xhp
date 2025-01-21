<?php

namespace X11;

class GetInputFocusRequest extends Request {

  public function __construct() {
    $this->sendRequest([
      ['opcode', 43, Type::BYTE],
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
  public static function GetInputFocus() {
▶
     1     1                               Reply
     1                                     revert-to
          0     None
          1     PointerRoot
          2     Parent
     2     CARD16                          sequence number
     4     0                               reply length
     4     WINDOW                          focus
          0     None
          1     PointerRoot
     20                                    unused
*/
