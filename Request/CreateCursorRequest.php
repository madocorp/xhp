<?php

namespace X11;

class CreateCursorRequest extends Request {

  public function __construct($cursor, $source, $mask, $foreRed, $foreGreen, $foreBlue, $backRed, $backGreen, $backBlue, $x, $y) {
    $this->sendRequest([
      ['opcode', 93, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 8, Type::CARD16],
      ['cursor', $cursor, Type::CURSOR],
      ['source', $source, Type::PIXMAP],
      ['mask', $mask, Type::PIXMAP],
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

}
