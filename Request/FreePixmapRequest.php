<?php

namespace X11;

class FreePixmapRequest extends Request {

  public function __construct($pixmap) {
    $opcode = 54;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['pixmap', Type::PIXMAP]
    ], $values);
  }

}
