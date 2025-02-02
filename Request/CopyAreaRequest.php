<?php

namespace X11;

class CopyAreaRequest extends Request {

  public function __construct($srcDrawable, $dstDrawable, $gc, $srcX, $srcY, $dstX, $dstY, $width, $height) {
    $opcode = 62;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['srcDrawable', Type::DRAWABLE],
      ['dstDrawable', Type::DRAWABLE],
      ['gc', Type::GCONTEXT],
      ['srcX', Type::INT16],
      ['srcY', Type::INT16],
      ['dstX', Type::INT16],
      ['dstY', Type::INT16],
      ['width', Type::CARD16],
      ['height', Type::CARD16]
    ], $values);
  }

}
