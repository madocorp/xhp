<?php

namespace X11;

class StoreColorsRequest extends Request {

  public function __construct($cmap, $colors) {
    $opcode = 89;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['cmap', Type::COLORMAP],
      ['colors', Type::FLIST, [
        ['pixel', Type::CARD32],
        ['red', Type::CARD16],
        ['green', Type::CARD16],
        ['blue', Type::CARD16],
        ['doColors', Type::BYTE],
        ['pad', Type::BYTE]
      ]]
    ], $values);
  }

}
