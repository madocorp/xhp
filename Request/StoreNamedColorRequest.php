<?php

namespace X11;

class StoreNamedColorRequest extends Request {

  public function __construct($doColors, $colormap, $pixel, $name) {
    $length = strlen($name);
    $pad = Connection::pad4($length);
    $this->sendRequest([
      ['opcode', 90, Type::BYTE],
      ['doColors', $doColors, Type::BYTE],
      ['requestLength', 4 + (($length + $pad) >> 2), Type::CARD16],
      ['cmap', $colormap, Type::COLORMAP],
      ['pixel', $pixel, Type::CARD32],
      ['n', $length, Type::CARD16],
      ['unused', 0, Type::CARD16],
      ['name', $name, Type::STRING8],
      ['pad', $pad, Type::PAD4]
    ]);
  }


  protected function processResponse() {
    return false;
  }

}
