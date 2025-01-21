<?php

namespace X11;

class GetScreenSaverRequest extends Request {

  public function __construct() {
    $this->sendRequest([
      ['opcode', 108, Type::BYTE],
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
  public static function GetScreenSaver() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     0                               reply length
     2     CARD16                          timeout
     2     CARD16                          interval
     1                                     prefer-blanking
          0     No
          1     Yes
     1                                     allow-exposures
          0     No
          1     Yes
     18                                    unused
*/
