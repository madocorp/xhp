<?php

namespace X11;

class CreateCursorRequest extends Request {

  public function __construct($cursor, $source, $mask, $foreRed, $foreGreen, $foreBlue, $backRed, $backGreen, $backBlue, $x, $y) {
    $opcode = 93;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['cursor', Type::CURSOR],
      ['source', Type::PIXMAP],
      ['mask', Type::PIXMAP],
      ['foreRed', Type::CARD16],
      ['foreGreen', Type::CARD16],
      ['foreBlue', Type::CARD16],
      ['backRed', Type::CARD16],
      ['backGreen', Type::CARD16],
      ['backBlue', Type::CARD16],
      ['x', Type::INT16],
      ['y', Type::INT16]
    ], $values);
  }

}
