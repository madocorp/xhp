<?php

namespace X11;

class UngrabPointerRequest extends Request {

  public function __construct($timestamp) {
    $this->doRequest([
      ['opcode', 27, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['timestamp', $timestamp, Type::TIMESTAMP]
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
