<?php

namespace X11;

class OpenFontRequest extends Request {

  public function __construct($fid, $name) {
    $this->sendRequest([
      ['opcode', 45, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['fid', $fid, Type::FONT],
      ['lengthOfName', strlen($name), Type::CARD16],
      ['unused', 0, Type::CARD16],
      ['name', $name, Type::STRING8],
    ]);
  }

}
