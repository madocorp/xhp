<?php

namespace X11;

class UngrabButtonRequest extends Request {

  public function __construct($button, $window, $modifiers) {
    $this->doRequest([
      ['opcode', 29, Type::BYTE],
      ['button', $button, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['modifiers', $modifiers, Type::CARD16],
      ['unused', 0, Type::CARD16]
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
