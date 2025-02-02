<?php

namespace X11;

class FillPolyRequest extends Request {

  public function __construct($drawable, $gc, $shape, $coordinateMode, $points) {
    $opcode = 69;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['drawable', Type::DRAWABLE],
      ['gc', Type::GCONTEXT],
      ['shape', Type::ENUM8, ['Complex', 'Nonconvex', 'Convex']],
      ['coordinateMode', Type::ENUM8, ['Origin', 'Previous']],
      ['unused', Type::UNUSED, 2],
      ['points', Type::FLIST, [
        ['x', Type::INT16],
        ['y', Type::INT16]
      ]]
    ], $values);
  }

}
