<?php

namespace X11;

class SetSelectionOwnerRequest extends Request {

  public function __construct($window, $selection, $timestamp) {
    $this->sendRequest([
      ['opcode', 22, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4, Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['selection', $selection, Type::ATOM],
      ['timestamp', $timestamp, Type::TIMESTAMP]
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
