<?php

namespace X11;

class CreateGlyphCursorRequest extends Request {

  public function __construct($cursor, $sourceFont, $maskFont, $sourceChar, $maskChar, $foreRed, $foreGree, $foreBlue, $backRed, $backGreen, $backBlue) {
    $this->doRequest([
      ['opcode', 94, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 8, Type::CARD16],
      ['cursor', $cursor, Type::CURSOR],
      ['sourceFont', $sourceFont, Type::FONT],
      ['maskFont', $maskFont, Type::FONT],
      ['sourceChar', $sourcChar, Type::INT16],
      ['maskChar', $maskChar, Type::INT16],
      ['foreRed', $foreRed, Type::CARD16],
      ['foreGreen', $foreGreen, Type::CARD16],
      ['foreBlue', $foreBlue, Type::CARD16],
      ['backRed', $backRed, Type::CARD16],
      ['backGreen', $backGreen, Type::CARD16],
      ['backBlue', $backBlue, Type::CARD16],
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
