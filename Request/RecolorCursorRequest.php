<?php

namespace X11;

class RecolorCursorRequest extends Request {

  public function __construct($cursor, $foreRed, $foreGreen, $foreBlue, $backRed, $backGreen, $backBlue) {
    $opcode = 96;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['cursor', Type::CURSOR],
      ['foreRed', Type::CARD16],
      ['foreGreen', Type::CARD16],
      ['foreBlue', Type::CARD16],
      ['backRed', Type::CARD16],
      ['backGreen', Type::CARD16],
      ['backBlue', Type::CARD16]
    ], $values);
  }

}
