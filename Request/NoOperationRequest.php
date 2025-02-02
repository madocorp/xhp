<?php

namespace X11;

class NoOperationRequest extends Request {

  public function __construct($n) {
    $noOperation = pack('C*', ...array_fill(0, $n * 4, 0));
    $opcode = 127;
    unset($n);
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['noOperation', Type::STRING8]
    ], $values);
  }

}
