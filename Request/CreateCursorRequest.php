<?php

namespace X11;

class CreateCursorRequest extends Request {

  public function __construct($cursor, $source, $mask, $foreRed, $foreGree, $foreBlue, $backRed, $backGreen, $backBlue, $x, $y) {
    $this->doRequest([
      ['opcode', 93, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 8, Type::CARD16],
      ['cursor', $cursor, Type::CURSOR],
      ['source', $cursor, Type::PIXMAP],
      ['mask', $cursor, Type::PIXMAP],
      ['foreRed', $foreRed, Type::CARD16],
      ['foreGreen', $foreGreen, Type::CARD16],
      ['foreBlue', $foreBlue, Type::CARD16],
      ['backRed', $backRed, Type::CARD16],
      ['backGreen', $backGreen, Type::CARD16],
      ['backBlue', $backBlue, Type::CARD16],
      ['x', $x, Type::INT16],
      ['y', $y, Type::INT16]
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
