<?php

namespace X11;

class CopyColormapAndFreeRequest extends Request {

  public function __construct($mid, $srcColormap) {
    $this->sendRequest([
      ['opcode', 80, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['mid', $mid, Type::COLORMAP],
      ['srcCmap', $srcColormap, Type::COLORMAP],
    ]);
  }

}
