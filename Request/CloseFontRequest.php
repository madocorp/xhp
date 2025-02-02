<?php

namespace X11;

class CloseFontRequest extends Request {

  public function __construct($fid) {
    $opcode = 46;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['fid', Type::FONT]
    ], $values);
  }

}
