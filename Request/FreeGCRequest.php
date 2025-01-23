<?php

namespace X11;

class FreeGCRequest extends Request {

  public function __construct($gc) {
    $this->sendRequest([
      ['opcode', 60, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['gc', $gc, Type::GCONTEXT]
    ]);
  }

}
