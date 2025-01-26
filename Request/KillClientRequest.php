<?php

namespace X11;

class KillClientRequest extends Request {

  public function __construct($resource) {
    $this->sendRequest([
      ['opcode', 113, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['resource', $resource, Type::CARD32]
    ]);
  }

}
