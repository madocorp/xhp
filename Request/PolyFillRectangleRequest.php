<?php

namespace X11;

class PolyFillRectangleRequest extends Request {

  public function __construct($drawable, $gc, $rectangles) {
    $this->sendRequest([
      ['opcode', 70, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCONTEXT],
      ['rectangles', $rectangles, Type::FLIST, [
        ['x', Type::INT16],
        ['y', Type::INT16],
        ['width', Type::CARD16],
        ['height', Type::CARD16]
      ]]
    ]);

  }

}
