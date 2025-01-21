<?php

namespace X11;

class AllocNamedColorRequest extends Request {

  public function __construct($name) {
    $length = strlen($name);
    $this->doRequest([
      ['opcode', 85, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['length', $length, Type::CARD16],
      ['unused', 0, Type::CARD16],
      ['name', $name, Type::STRING8],
      ['pad', Connection::pad4($length), Type::PAD4],
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}
