<?php

namespace X11;

class FreeColorsRequest extends Request {

  public function __construct($colormap, $planeMask, $pixels) {
    $opcode = 88;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['colormap', Type::COLORMAP],
      ['planeMask', Type::CARD32],
      ['pixels', Type::FLIST, [
        ['pixel', Type::CARD32]
      ]]
    ], $values);
  }

}
