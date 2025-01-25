<?php

namespace X11;

class FreeColorsRequest extends Request {

  public function __construct($colormap, $planeMask, $pixels) {
    $this->sendRequest([
      ['opcode', 88, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['colormap', $colormap, Type::COLORMAP],
      ['planeMask', $planeMask, Type::CARD32],
      ['pixels', $pixels, Type::FLIST, [
        ['pixel', Type::CARD32]
      ]]
    ]);
  }

}
