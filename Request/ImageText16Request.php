<?php

namespace X11;

class ImageText16Request extends Request {

  public function __construct($drawable, $gc, $x, $y, $string) {
    $length = mb_strlen($string);
    $string = mb_convert_encoding($string, "ISO-10646-UCS-2", "UTF-8");
    $this->sendRequest([
      ['opcode', 77, Type::BYTE],
      ['length', $length, Type::CARD8],
      ['requestLength', 4, Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCONTEXT],
      ['x', $x, Type::INT16],
      ['y', $y, Type::INT16],
      ['string', $string, Type::STRING8]
    ]);
  }

}

