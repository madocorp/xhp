<?php

namespace X11;

class PolySegmentRequest extends Request {

  public function __construct($drawable, $gc, $segments) {
    $data = [
      ['opcode', 66, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3 + 2 * count($segments), Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCCONTEXT],
    ];
    foreach ($segments as $segment) {
      $data[] = ['x1', $segment['x1'], Type::INT16];
      $data[] = ['y1', $segment['y1'], Type::INT16];
      $data[] = ['x2', $segment['x2'], Type::INT16];
      $data[] = ['y2', $segment['y2'], Type::INT16];
    }
    $this->doRequest($data);
  }

  protected function processResponse() {
    return false;
  }

}
