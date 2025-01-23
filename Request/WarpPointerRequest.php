<?php

namespace X11;

class WarpPointerRequest extends Request {

  public function __construct($srcWindow, $dstWindow, $srcX, $srcY, $srcWidth, $srcHeight, $dstX, $dstY) {
    $this->sendRequest([
      ['opcode', 41, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 6, Type::CARD16],
      ['srcWindow', $srcWindow, Type::WINDOW],
      ['dstWindow', $dstWindow, Type::WINDOW],
      ['srcX', $srcX, Type::INT16],
      ['srcY', $srcY, Type::INT16],
      ['srcWidth', $srcWidth, Type::CARD16],
      ['srcHeight', $srcHeight, Type::CARD16],
      ['dstX', $srcX, Type::INT16],
      ['dstY', $srcY, Type::INT16]
    ]);
  }

}
