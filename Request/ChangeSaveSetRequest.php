<?php

namespace X11;

class ChangeSaveSetRequest extends Request {

  public function __construct($mode, $window) {
    $opcode = 6;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['mode', Type::ENUM8, ['Insert', 'Delete']],
      ['requestLength', Type::CARD16],
      ['window', Type::WINDOW]
    ], $values);
  }

}
