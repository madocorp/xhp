<?php

namespace X11;

class StoreNamedColorRequest extends Request {

  public function __construct($doColors, $cmap, $pixel, $name) {
    $opcode = 90;
    $n = strlen($name);
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['doColors', Type::BYTE],
      ['requestLength', Type::CARD16],
      ['cmap', Type::COLORMAP],
      ['pixel', Type::CARD32],
      ['n', Type::CARD16],
      ['unused', Type::UNUSED, 2],
      ['name', Type::STRING8]
    ], $values);
  }

}
