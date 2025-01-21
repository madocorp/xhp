<?php

namespace X11;

class RecolorCursorRequest extends Request {

  public function __construct($cursor, $foreRed, $foreGree, $foreBlue, $backRed, $backGreen, $backBlue) {
    $this->doRequest([
      ['opcode', 96, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 5, Type::CARD16],
      ['cursor', $cursor, Type::CURSOR],
      ['foreRed', $foreRed, Type::CARD16],
      ['foreGreen', $foreGreen, Type::CARD16],
      ['foreBlue', $foreBlue, Type::CARD16],
      ['backRed', $backRed, Type::CARD16],
      ['backGreen', $backGreen, Type::CARD16],
      ['backBlue', $backBlue, Type::CARD16]
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
