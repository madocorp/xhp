<?php

namespace X11;

class GetKeyboardControlRequest extends Request {

  public function __construct() {
    $this->sendRequest([
      ['opcode', 103, Type::BYTE],
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
  public static function GetKeyboardControl() {
▶
     1     1                               Reply
     1                                     global-auto-repeat
          0     Off
          1     On
     2     CARD16                          sequence number
     4     5                               reply length
     4     CARD32                          led-mask
     1     CARD8                           key-click-percent
     1     CARD8                           bell-percent
     2     CARD16                          bell-pitch
     2     CARD16                          bell-duration
     2                                     unused
     32     LISTofCARD8                    auto-repeats
*/
