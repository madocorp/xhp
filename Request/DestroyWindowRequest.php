<?php

namespace X11;

class DestroyWindowRequest extends Request {

  public function __construct($window) {
    $this->doRequest([
      ['opcode', 4, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['window', $window, Type::WINDOW]
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
