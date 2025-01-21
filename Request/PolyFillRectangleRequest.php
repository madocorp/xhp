<?php

namespace X11;

class PolyFillRectangleRequest extends Request {

  public function __construct($drawable, $gc, $rectangles) {
    $data = [
      ['opcode', 70, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3 + 2 * count($rectangles), Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCCONTEXT],
    ];
    foreach ($rectangles as $rectangle) {
      $data[] = ['x', $rectangle['x'], Type::INT16];
      $data[] = ['y', $rectangle['y'], Type::INT16];
      $data[] = ['width', $rectangle['width'], Type::CARD16];
      $data[] = ['height', $rectangle['height'], Type::CARD16];
    }
    $this->doRequest($data);
  }

  protected function processResponse() {
    return false;
  }

}
