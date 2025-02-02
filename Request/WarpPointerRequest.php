<?php

namespace X11;

class WarpPointerRequest extends Request {

  public function __construct($srcWindow, $dstWindow, $srcX, $srcY, $srcWidth, $srcHeight, $dstX, $dstY) {
    $opcode = 41;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['srcWindow', Type::WINDOW],
      ['dstWindow', Type::WINDOW],
      ['srcX', Type::INT16],
      ['srcY', Type::INT16],
      ['srcWidth', Type::CARD16],
      ['srcHeight', Type::CARD16],
      ['dstX', Type::INT16],
      ['dstY', Type::INT16]
    ], $values);
  }

}
