<?php

namespace X11;

class CopyColormapAndFreeRequest extends Request {

  public function __construct($mid, $srcCmap) {
    $opcode = 80;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['mid', Type::COLORMAP],
      ['srcCmap', Type::COLORMAP]
    ], $values);
  }

}
