<?php

namespace X11;

class PolyPointRequest extends Request {

  public function __construct($coordinateMode, $drawable, $gc, $points) {
    $this->sendRequest([
      ['opcode', 64, Type::BYTE],
      ['coordinateMode', $coordinateMode, Type::ENUM8, ['Origin', 'Previous']],
      ['requestLength', 3, Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCONTEXT],
      ['points', $points, Type::FLIST, [
        ['x', Type::INT16],
        ['y', Type::INT16]
      ]]
    ]);
  }

}
