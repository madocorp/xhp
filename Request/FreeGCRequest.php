<?php

namespace X11;

class FreeGCRequest extends Request {

  public function __construct($gc) {
    $this->doRequest([
      ['opcode', 60, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['gc', $gc, Type::GCCONTEXT]
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
