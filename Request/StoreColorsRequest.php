<?php

namespace X11;

class StoreColorsRequest extends Request {

  public function __construct($colormap, $colors, $doColors) {
    $data = [
      ['opcode', 89, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2 + count($color) * 3, Type::CARD16],
      ['cmap', $colormap, Type::COLORMAP],
    ];
    foreach ($colors as $color) {
      $data[] = ['pixel', $color['pixel'], Type::CARD32];
      $data[] = ['red', $color['red'], Type::CARD32];
      $data[] = ['green', $color['green'], Type::CARD32];
      $data[] = ['blue', $color['blue'], Type::CARD32];
      $data[] = ['doColors', $doColors, Type::BYTE];
      $data[] = ['unused', 0, Type::BYTE];
    }
    $this->doRequest($data);
  }

  protected function processResponse() {
    return false;
  }

}
