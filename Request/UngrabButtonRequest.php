<?php

namespace X11;

class UngrabButtonRequest extends Request {

  public function __construct($button, $window, $modifiers) {
    $opcode = 29;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['button', Type::BYTE],
      ['requestLength', Type::CARD16],
      ['window', Type::WINDOW],
      ['modifiers', Type::CARD16],
      ['unused', Type::UNUSED, 2]
    ], $values);
  }

}
