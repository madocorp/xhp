<?php

namespace X11;

class PolyPointRequest extends Request {

  public function __construct($coordinateMode, $drawable, $gc, $points) {
    $opcode = 64;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['coordinateMode', Type::ENUM8, ['Origin', 'Previous']],
      ['requestLength', Type::CARD16],
      ['drawable', Type::DRAWABLE],
      ['gc', Type::GCONTEXT],
      ['points', Type::FLIST, [
        ['x', Type::INT16],
        ['y', Type::INT16]
      ]]
    ], $values);
  }

}
