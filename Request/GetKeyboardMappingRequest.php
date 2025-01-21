<?php

namespace X11;

class GetKeyboardMappingRequest extends Request {

  public function __construct($firstKeycode, $count) {
    $this->doRequest([
      ['opcode', 101, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['firstKeycode', $firstKeycode, Type::KEYCODE],
      ['count', $count, Type::BYTE],
      ['unused', 0, Type::CARD16]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}
