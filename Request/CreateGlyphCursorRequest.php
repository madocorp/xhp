<?php

namespace X11;

class CreateGlyphCursorRequest extends Request {

  public function __construct($cursor, $sourceFont, $maskFont, $sourceChar, $maskChar, $foreRed, $foreGreen, $foreBlue, $backRed, $backGreen, $backBlue) {
    $this->sendRequest([
      ['opcode', 94, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 8, Type::CARD16],
      ['cursor', $cursor, Type::CURSOR],
      ['sourceFont', $sourceFont, Type::FONT],
      ['maskFont', $maskFont, Type::FONT],
      ['sourceChar', $sourceChar, Type::INT16],
      ['maskChar', $maskChar, Type::INT16],
      ['foreRed', $foreRed, Type::CARD16],
      ['foreGreen', $foreGreen, Type::CARD16],
      ['foreBlue', $foreBlue, Type::CARD16],
      ['backRed', $backRed, Type::CARD16],
      ['backGreen', $backGreen, Type::CARD16],
      ['backBlue', $backBlue, Type::CARD16],
    ]);
  }

}
