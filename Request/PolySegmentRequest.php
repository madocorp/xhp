<?php

namespace X11;

class PolySegmentRequest extends Request {

  public function __construct($drawable, $gc, $segments) {
    $this->sendRequest([
      ['opcode', 66, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCONTEXT],
      ['segments', $segments, Type::FLIST, [
        ['x1', Type::INT16],
        ['y1', Type::INT16],
        ['x2', Type::INT16],
        ['y2', Type::INT16]
      ]]
    ]);
  }

}
