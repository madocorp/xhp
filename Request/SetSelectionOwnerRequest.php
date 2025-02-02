<?php

namespace X11;

class SetSelectionOwnerRequest extends Request {

  public function __construct($window, $selection, $timestamp) {
    $opcode = 22;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['window', Type::WINDOW],
      ['selection', Type::ATOM],
      ['timestamp', Type::TIMESTAMP]
    ], $values);
  }

}
