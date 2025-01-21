<?php

namespace X11;

class GetMotionEventsRequest extends Request {

  public function __construct($window, $start, $stop) {
    $this->doRequest([
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
