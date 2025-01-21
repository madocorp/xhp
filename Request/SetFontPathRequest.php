<?php

namespace X11;

class SetFontPathRequest extends Request {

  public function __construct($path) {
    $length = strlen($path);
    $this->sendRequest([
      ['opcode', 51, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['numberOfStrings', $n, Type::CARD16],
      ['unused', 0, Type::CARD16],
      ['lengthOfPattern', $length, Type::PAD4],
      ['path', $path, Type::STRING8],
      ['pad', Connection::pad4($length), Type::PAD4]
    ]);

  }

  protected function processResponse() {
    return false;
  }

}
