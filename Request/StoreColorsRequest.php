<?php

namespace X11;

class StoreColorsRequest extends Request {

  public function __construct($colormap, $colors) {
    $this->sendRequest([
      ['opcode', 89, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['cmap', $colormap, Type::COLORMAP],
      ['colors', $colors, Type::FLIST, [
        ['pixel', Type::CARD32],
        ['red', Type::CARD16],
        ['green', Type::CARD16],
        ['blue', Type::CARD16],
        ['doColors', Type::BYTE],
        ['pad', Type::BYTE]
      ]]
    ]);
  }

}
