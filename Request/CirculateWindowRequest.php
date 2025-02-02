<?php

namespace X11;

class CirculateWindowRequest extends Request {

  public function __construct($direction, $window) {
    $opcode = 13;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['direction', Type::ENUM8, ['RaiseLowest', 'LowerHeighest']],
      ['requestLength', Type::CARD16],
      ['window', Type::WINDOW]
    ], $values);
  }

}
