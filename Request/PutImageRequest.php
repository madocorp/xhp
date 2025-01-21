<?php

namespace X11;

class PutImageRequest extends Request {

  public function __construct($format, $drawable, $gc, $width, $height, $dstX, $dstY, $leftPad, $depth, $imageData) {
    $length = strlen($imageData);
    $pad = Connection::pad4($length);
    $this->doRequest([
      ['opcode', 72, Type::BYTE],
      ['format', $format, Type::ENUM8, ['Bitmap', 'XYPixmap', 'ZPixmap']],
      ['requestLength', 6 + (($length + $pad) >> 2), Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCCONTEXT],
      ['width', $width, Type::CARD16],
      ['height', $height, Type::CARD16],
      ['dstX', $dstX, Type::INT16],
      ['dstY', $dstY, Type::INT16],
      ['leftPad', $depth, Type::CARD8],
      ['unused', 0, Type::CARD16],
      ['data', $imageData, Type::STRING8],
      ['pad', $pad, Type::PAD4]
    ]);
  }


  protected function processResponse() {
    return false;
  }

}
