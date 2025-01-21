<?php

namespace X11;

class ClearAreaRequest extends Request {

  public function __construct($exposures, $window, $x, $y, $width, $height) {
    $this->sendRequest([
      ['opcode', 61, Type::BYTE],
      ['exposures', $exposures, Type::BOOL],
      ['requestLength', 4, Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['x', $x, Type::INT16],
      ['y', $y, Type::INT16],
      ['width', $width, Type::CARD16],
      ['height', $height, Type::CARD16]
    ]);
  }

}
