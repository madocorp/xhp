<?php

namespace X11;

class KillClientRequest extends Request {

  public function __construct($resource) {
    $opcode = 113;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['resource', Type::CARD32]
    ], $values);
  }

}
