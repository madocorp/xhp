<?php

namespace X11;

class FreePixmapRequest extends Request {

  public function __construct($pixmap) {
    $this->sendRequest([
      ['opcode', 54, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['pixmap', $pixmap, Type::PIXMAP]
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
