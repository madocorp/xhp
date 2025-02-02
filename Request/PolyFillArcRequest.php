<?php

namespace X11;

class PolyFillArcRequest extends Request {

  public function __construct($drawable, $gc, $arcs) {
    $opcode = 71;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['drawable', Type::DRAWABLE],
      ['gc', Type::GCONTEXT],
      ['arcs', Type::FLIST, [
        ['x', Type::INT16],
        ['y', Type::INT16],
        ['width', Type::CARD16],
        ['height', Type::CARD16],
        ['angle1', Type::INT16],
        ['angle2', Type::INT16]
      ]]
    ], $values);
  }

}
