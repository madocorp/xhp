<?php

namespace X11;

class CreatePixmapRequest extends Request {

  public function __construct($depth, $pid, $drawable, $width, $height) {
    $opcode = 53;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['depth', Type::CARD8],
      ['requestLength', Type::CARD16],
      ['pid', Type::PIXMAP],
      ['drawable', Type::DRAWABLE],
      ['width', Type::CARD16],
      ['height', Type::CARD16]
    ], $values);
  }

}
