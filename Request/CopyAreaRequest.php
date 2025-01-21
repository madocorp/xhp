<?php

namespace X11;

class CopyAreaRequest extends Request {

  public function __construct($srcDrawable, $dstDrawable, $gc, $srcX, $srcY, $dstX, $dstY, $width, $height) {
    $this->doRequest([
      ['opcode', 62, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 7, Type::CARD16],
      ['srcDrawable', $srcDrawable, Type::DRAWABLE],
      ['dstDrawable', $dstDrawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCCONTEXT],
      ['srcX', $srcX, Type::INT16],
      ['srcY', $srcY, Type::INT16],
      ['dstX', $dstX, Type::INT16],
      ['dstY', $dstY, Type::INT16],
      ['width', $width, Type::CARD16],
      ['height', $height, Type::CARD16]
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
