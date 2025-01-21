<?php

namespace X11;

class FreeColormapRequest extends Request {

  public function __construct($window, $colormap) {
    $this->sendRequest([
      ['opcode', 79, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4, Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['cmap', $colormap, Type::VISUALID],
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
