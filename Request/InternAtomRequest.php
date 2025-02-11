<?php

namespace X11;

class InternAtomRequest extends Request {

  public function __construct($name) {
    $length = strlen($name);
    $this->doRequest([
      ['opcode', 16, Type::BYTE],
      ['onlyIfExists', 0, Type::BOOL],
      ['requestLength', 2, Type::CARD16],
      ['n', $length, Type::CARD16],
      ['unused', 0, Type::CARD16],
      ['name', $name, Type::STRING8],
      ['pad', pad4($length), Type::PAD4]
    ]);
    return Respnose::InternAtom();
  }

  protected function processResponse() {
    return false;
  }

}
