<?php

namespace X11;

class ReparentWindowRequest extends Request {

  public function __construct($window, $parent, $x, $y) {
    $this->sendRequest([
      ['opcode', 7, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4, Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['parent', $parent, Type::WINDOW],
      ['x', $x, Type::INT16],
      ['y', $y, Type::INT16],
    ]);
  }

}
