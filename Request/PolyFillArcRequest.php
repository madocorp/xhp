<?php

namespace X11;

class PolyFillArcRequest extends Request {

  public function __construct($drawable, $gc, $arcs) {
    $this->sendRequest([
      ['opcode', 71, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCONTEXT],
      ['arcs', $arcs, Type::FLIST, [
        ['x', Type::INT16],
        ['y', Type::INT16],
        ['width', Type::CARD16],
        ['height', Type::CARD16],
        ['angle1', Type::INT16],
        ['angle2', Type::INT16]
      ]]
    ]);
  }

}
