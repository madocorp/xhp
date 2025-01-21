<?php

namespace X11;

class ListInstalledColormapsRequest extends Request {

  public function __construct($window) {
    $this->doRequest([
      ['opcode', 83, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['window', $window, Type::WINDOW]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}
