<?php

namespace X11;

class PutImageRequest extends Request {

  public function __construct($format, $drawable, $gc, $width, $height, $dstX, $dstY, $leftPad, $depth, $imageData) {
    $this->sendRequest([
      ['opcode', 72, Type::BYTE],
      ['format', $format, Type::ENUM8, ['Bitmap', 'XYPixmap', 'ZPixmap']],
      ['requestLength', 6, Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCONTEXT],
      ['width', $width, Type::CARD16],
      ['height', $height, Type::CARD16],
      ['dstX', $dstX, Type::INT16],
      ['dstY', $dstY, Type::INT16],
      ['leftPad', $leftPad, Type::CARD8],
      ['depth', $depth, Type::CARD8],
      ['unused', 0, Type::CARD16],
      ['data', $imageData, Type::STRING8]
    ]);
  }

}
