<?php

namespace X11;

class NoOperationRequest extends Request {

  public function __construct($n) {
    $nop = pack('C*', ...array_fill(0, $n * 4, 0));
    $this->sendRequest([
      ['opcode', 127, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 1, Type::CARD16],
      ['noOperation', $nop, Type::STRING8]
    ]);
  }

}
