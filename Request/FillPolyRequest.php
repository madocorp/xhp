<?php

namespace X11;

class FillPolyRequest extends Request {

  public function __construct($drawable, $gc, $shape, $coordinateMode, $points) {
    $data = [
      ['opcode', 69, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4 + count($points), Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCCONTEXT],
      ['shape', $shape, Type::ENUM, ['Complex', 'Nonconvex', 'Convex']],
      ['coordinateMode', $coordinateMode, Type::ENUM, ['Origin', 'Previous']],
      ['unused', 0, Type::CARD16]
    ];
    foreach ($points as $point) {
      $data[] = ['x', $point['x'], Type::INT16];
      $data[] = ['y', $point['y'], Type::INT16];
    }
    $this->doRequest($data);
  }

  protected function processResponse() {
    return false;
  }

}
