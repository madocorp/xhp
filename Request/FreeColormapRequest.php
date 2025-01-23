<?php

namespace X11;

class FreeColormapRequest extends Request {

  public function __construct( $colormap) {
    $this->sendRequest([
      ['opcode', 79, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['cmap', $colormap, Type::VISUALID]
    ]);
  }

}
