<?php

namespace X11;

class SetInputFocusRequest extends Request {

  public function __construct($revertTo, $srcWindow, $timestamp) {
    $opcode = 42;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['revertTo', Type::ENUM8, ['None', 'PointerRoot', 'Parent']],
      ['requestLength', Type::CARD16],
      ['srcWindow', Type::WINDOW],
      ['timestamp', Type::CARD32]
    ], $values);
  }

}
