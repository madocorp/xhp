<?php

namespace X11;

class CreateGlyphCursorRequest extends Request {

  public function __construct($cursor, $sourceFont, $maskFont, $sourceChar, $maskChar, $foreRed, $foreGreen, $foreBlue, $backRed, $backGreen, $backBlue) {
    $opcode = 94;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['cursor', Type::CURSOR],
      ['sourceFont', Type::FONT],
      ['maskFont', Type::FONT],
      ['sourceChar', Type::INT16],
      ['maskChar', Type::INT16],
      ['foreRed', Type::CARD16],
      ['foreGreen', Type::CARD16],
      ['foreBlue', Type::CARD16],
      ['backRed', Type::CARD16],
      ['backGreen', Type::CARD16],
      ['backBlue', Type::CARD16]
    ], $values);
  }

}
