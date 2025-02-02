<?php

namespace X11;

class PolyRectangleRequest extends Request {

  public function __construct($drawable, $gc, $rectangles) {
    $opcode = 67;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['drawable', Type::DRAWABLE],
      ['gc', Type::GCONTEXT],
      ['rectangles', Type::FLIST, [
        ['x', Type::INT16],
        ['y', Type::INT16],
        ['width', Type::CARD16],
        ['height', Type::CARD16]
      ]]
    ], $values);
  }

}
