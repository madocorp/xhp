<?php

namespace X11;

class CloseFontRequest extends Request {

  public function __construct($fid) {
    $this->sendRequest([
      ['opcode', 46, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['fid', $fid, Type::FONT]
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
