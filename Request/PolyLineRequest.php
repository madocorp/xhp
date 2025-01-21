<?php

namespace X11;

class PolyLineRequest extends Request {

  public function __construct($coordinateMode, $drawable, $gc, $points) {
    $data = [
      ['opcode', 65, Type::BYTE],
      ['coordinateMode', $coordinateMode, Type::ENUM8, ['Origin', 'Previous']],
      ['requestLength', 3 + count($points), Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCCONTEXT],
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
