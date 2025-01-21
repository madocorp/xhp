<?php

namespace X11;

class SetDashesRequest extends Request {

  public function __construct($gc, $dashOffset, $dashes) {
    $length = strlen($dashes);
    $this->sendRequest([
      ['opcode', 58, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['gc', $gc, Type::GCONTEXT],
      ['dashOffset', $dashOffset, Type::CARD16],
      ['n', $length, Type::CARD16],
      ['dashes', $dashes, Type::STRING8],
      ['pad', Connection::pad4($length), Type::PAD4]
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
