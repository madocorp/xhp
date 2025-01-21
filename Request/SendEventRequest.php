<?php

namespace X11;

class SendEventRequest extends Request {

  public function __construct($propagate, $destination, $eventMask, $event) {
    $this->doRequest([
      ['opcode', 25, Type::BYTE],
      ['propagate', 0, Type::BOOL],
      ['requestLength', 11, Type::CARD16],
      ['destination', $destination, Type::WINDOW],
      ['eventMask', $eventMask, Type::CARD32],
      ['event', $event, Type::EVENT]
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
