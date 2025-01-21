<?php

namespace X11;

class CirculateWindowRequest extends Request {

  public function __construct($direction, $window) {
    $this->doRequest([
      ['opcode', 13, Type::BYTE],
      ['direction', 0, Type::ENUM8, ['RaiseLowest', 'LowerHeighest']],
      ['requestLength', 2, Type::CARD16],
      ['window', $window, Type::WINDOW]
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
