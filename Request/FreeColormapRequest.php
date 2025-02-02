<?php

namespace X11;

class FreeColormapRequest extends Request {

  public function __construct( $cmap) {
    $opcode = 79;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['cmap', Type::VISUALID]
    ], $values);
  }

}
