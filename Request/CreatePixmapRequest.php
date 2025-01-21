<?php

namespace X11;

class CreatePixmapRequest extends Request {

  public function __construct($depth, $pid, $drawable, $width, $height) {
    $this->doRequest([
      ['opcode', 53, Type::BYTE],
      ['depth', $depth, Type::CARD8],
      ['requestLength', 4, Type::CARD16],
      ['pid', $pid, Type::PIXMAP],
      ['drawable', $drawable, Type::DRAWABLE],
      ['width', $width, Type::CARD16],
      ['height', $height, Type::CARD16]
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
