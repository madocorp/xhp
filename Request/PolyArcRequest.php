<?php

namespace X11;

class PolyArcRequest extends Request {

  public function __construct($drawable, $gc, $arcs) {
    $data = [
      ['opcode', 68, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3 + 3 * count($arcs), Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCCONTEXT],
    ];
    foreach ($arcs as $arc) {
      $data[] = ['x', $arc['x'], Type::INT16];
      $data[] = ['y', $arc['y'], Type::INT16];
      $data[] = ['width', $arc['width'], Type::CARD16];
      $data[] = ['height', $arc['height'], Type::CARD16];
      $data[] = ['angle1', $arc['angle1'], Type::INT16];
      $data[] = ['angle2', $arc['angle2'], Type::INT16];
    }
    $this->doRequest($data);
  }

  protected function processResponse() {
    return false;
  }

}
