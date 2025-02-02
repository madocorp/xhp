<?php

namespace X11;

class InstallColormapRequest extends Request {

  public function __construct($colormap) {
    $opcode = 81;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['colormap', Type::COLORMAP]
    ], $values);
  }

}
