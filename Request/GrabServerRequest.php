<?php

namespace X11;

class GrabServerRequest extends Request {

  public function __construct() {
    $this->sendRequest([
      ['opcode', 36, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 1, Type::CARD16]
    ]);
  }

}
