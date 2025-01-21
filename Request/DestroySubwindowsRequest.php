<?php

namespace X11;

class DestroySubwindowsRequest extends Request {

  public function __construct($window) {
    $this->doRequest([
      ['opcode', 5, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['window', $window, Type::WINDOW]
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
