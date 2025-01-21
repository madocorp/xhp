<?php

namespace X11;

class GetMotionEventsRequest extends Request {

  public function __construct($window, $start, $stop) {
    $this->sendRequest([
      ['opcode', 39, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4, Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['start', $start, Type::CARD32],
      ['stop', $stop, Type::CARD32]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}

/*
  public static function GetMotionEvents() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     2n                              reply length
     4     n                               number of TIMECOORDs in events
     20                                    unused
     8n     LISTofTIMECOORD                events

  TIMECOORD
     4     TIMESTAMP                       time
     2     INT16                           x
     2     INT16                           y
*/
