<?php

namespace X11;

class BellRequest extends Request {

  public function __construct($percent) {
    $this->sendRequest([
      ['opcode', 104, Type::BYTE],
      ['percent', $percent, Type::INT8],
      ['requestLength', 1, Type::CARD16]
    ]);
  }

}
