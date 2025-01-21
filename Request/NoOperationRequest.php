<?php

namespace X11;

class NoOperationRequest extends Request {

  public function __construct($n) {
    $unused = pack('C*', array_fill(0, $n * 4, 0));
    $this->doRequest([
      ['opcode', 127, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 1 + $n, Type::CARD16],
      ['unused', $unused, Type::STRING8]
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
