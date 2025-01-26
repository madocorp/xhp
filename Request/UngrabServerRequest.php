<?php

namespace X11;

class UngrabServerRequest extends Request {

  public function __construct() {
    $this->sendRequest([
      ['opcode', 37, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 1, Type::CARD16]
    ]);
  }

}
