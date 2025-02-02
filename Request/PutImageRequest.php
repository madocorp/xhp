<?php

namespace X11;

class PutImageRequest extends Request {

  public function __construct($format, $drawable, $gc, $width, $height, $dstX, $dstY, $leftPad, $depth, $data) {
    $opcode = 72;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['format', Type::ENUM8, ['Bitmap', 'XYPixmap', 'ZPixmap']],
      ['requestLength', Type::CARD16],
      ['drawable', Type::DRAWABLE],
      ['gc', Type::GCONTEXT],
      ['width', Type::CARD16],
      ['height', Type::CARD16],
      ['dstX', Type::INT16],
      ['dstY', Type::INT16],
      ['leftPad', Type::CARD8],
      ['depth', Type::CARD8],
      ['unused', Type::UNUSED, 2],
      ['data', Type::STRING8, false]
    ], $values);
  }

}
