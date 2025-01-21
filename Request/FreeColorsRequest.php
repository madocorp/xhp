<?php

namespace X11;

class FreeColorsRequest extends Request {

  public function __construct($colormap, $planeMask, $pixels) {
    $data = [
      ['opcode', 88, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3 + count($pixels), Type::CARD16],
      ['colormap', $colormap, Type::COLORMAP],
      ['planeMask', $planeMask, Type::CARD32]
    ];
    foreach ($pixels as $pixel) {
      $data[] = ['pixel', $pixel, Type::CARD32];
    }
    $this->doRequest($data);
  }

  protected function processResponse() {
    return false;
  }

}
