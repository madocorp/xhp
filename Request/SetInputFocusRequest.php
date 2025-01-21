<?php

namespace X11;

class SetInputFocusRequest extends Request {

  public function __construct($revertTo, $window, $timestamp) {
    $this->sendRequest([
      ['opcode', 42, Type::BYTE],
      ['revertTo', $revertTo, Type::ENUM8, ['None', 'PointerRoot', 'Parent']],
      ['requestLength', 3, Type::CARD16],
      ['srcWindow', $srcWindow, Type::WINDOW],
      ['timestamp', $timestamp, Type::CARD32]
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
