<?php

namespace X11;

class UngrabKeyboardRequest extends Request {

  public function __construct() {
    $this->sendRequest([
      ['opcode', 32, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['timestamp', $timestamp, Type::CARD32]
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
