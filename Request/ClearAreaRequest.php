<?php

namespace X11;

class ClearAreaRequest extends Request {

  public function __construct($exposures, $window, $x, $y, $width, $height) {
    $opcode = 61;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['exposures', Type::BOOL],
      ['requestLength', Type::CARD16],
      ['window', Type::WINDOW],
      ['x', Type::INT16],
      ['y', Type::INT16],
      ['width', Type::CARD16],
      ['height', Type::CARD16]
    ], $values);
  }

}
