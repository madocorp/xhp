<?php

namespace X11;

class PolySegmentRequest extends Request {

  public function __construct($drawable, $gc, $segments) {
    $opcode = 66;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['drawable', Type::DRAWABLE],
      ['gc', Type::GCONTEXT],
      ['segments', Type::FLIST, [
        ['x1', Type::INT16],
        ['y1', Type::INT16],
        ['x2', Type::INT16],
        ['y2', Type::INT16]
      ]]
    ], $values);
  }

}
