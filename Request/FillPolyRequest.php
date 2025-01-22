<?php

namespace X11;

class FillPolyRequest extends Request {

  public function __construct($drawable, $gc, $shape, $coordinateMode, $points) {
    $this->sendRequest([
      ['opcode', 69, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4, Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCONTEXT],
      ['shape', $shape, Type::ENUM8, ['Complex', 'Nonconvex', 'Convex']],
      ['coordinateMode', $coordinateMode, Type::ENUM8, ['Origin', 'Previous']],
      ['unused', 0, Type::CARD16],
      ['points', $points, Type::FLIST, [
        ['x', Type::INT16],
        ['y', Type::INT16]
      ]]
    ]);
  }

}
