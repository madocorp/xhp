<?php

namespace X11;

class ReparentWindowRequest extends Request {

  public function __construct($window, $parent, $x, $y) {
    $opcode = 7;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['window', Type::WINDOW],
      ['parent', Type::WINDOW],
      ['x', Type::INT16],
      ['y', Type::INT16]
    ], $values);
  }

}
