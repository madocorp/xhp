<?php

namespace X11;

class CopyPlaneRequest extends Request {

  public function __construct($srcDrawable, $dstDrawable, $gc, $srcX, $srcY, $dstX, $dstY, $width, $height, $bitPlane) {
    $this->sendRequest([
      ['opcode', 63, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 8, Type::CARD16],
      ['srcDrawable', $srcDrawable, Type::DRAWABLE],
      ['dstDrawable', $dstDrawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCONTEXT],
      ['srcX', $srcX, Type::INT16],
      ['srcY', $srcY, Type::INT16],
      ['dstX', $dstX, Type::INT16],
      ['dstY', $dstY, Type::INT16],
      ['width', $width, Type::CARD16],
      ['height', $height, Type::CARD16],
      ['bitPlane', $bitPlane, Type::CARD32]
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
