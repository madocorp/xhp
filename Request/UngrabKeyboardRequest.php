<?php

namespace X11;

class UngrabKeyboardRequest extends Request {

  public function __construct($timestamp) {
    $this->sendRequest([
      ['opcode', 32, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['timestamp', $timestamp, Type::CARD32]
    ]);
  }

}
