<?php

namespace X11;

class FreeCursorRequest extends Request {

  public function __construct($cursor) {
    $opcode = 95;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['cursor', Type::CURSOR],
    ], $values);
  }

}
