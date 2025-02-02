<?php

namespace X11;

class DeletePropertyRequest extends Request {

  public function __construct($window, $property) {
    $opcode = 19;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['window', Type::WINDOW],
      ['property', Type::ATOM]
    ], $values);
  }

}
