<?php

namespace X11;

class MapSubwindowsRequest extends Request {

  public function __construct($window) {
    $this->sendRequest([
      ['opcode', 9, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['window', $window, Type::WINDOW]
    ]);
  }

}
