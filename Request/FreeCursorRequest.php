<?php

namespace X11;

class FreeCursorRequest extends Request {

  public function __construct($cursor) {
    $this->doRequest([
      ['opcode', 95, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['cursor', $cursor, Type::CURSOR],
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
