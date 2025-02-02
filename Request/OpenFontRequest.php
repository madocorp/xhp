<?php

namespace X11;

class OpenFontRequest extends Request {

  public function __construct($fid, $name) {
    $lengthOfName = strlen($name);
    $opcode = 45;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['fid', Type::FONT],
      ['lengthOfName', Type::CARD16],
      ['unused', Type::UNUSED, 2],
      ['name', Type::STRING8]
    ], $values);
  }

}
