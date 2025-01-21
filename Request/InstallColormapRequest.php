<?php

namespace X11;

class InstallColormapRequest extends Request {

  public function __construct($colormap) {
    $this->sendRequest([
      ['opcode', 81, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['colormap', $colormap, Type::COLORMAP]
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
