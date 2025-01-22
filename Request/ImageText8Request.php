<?php

namespace X11;

class ImageText8Request extends Request {

  public function __construct($drawable, $gc, $x, $y, $string) {
    $this->sendRequest([
      ['opcode', 76, Type::BYTE],
      ['length', strlen($string), Type::CARD8],
      ['requestLength', 4, Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCONTEXT],
      ['x', $x, Type::INT16],
      ['y', $y, Type::INT16],
      ['string', $string, Type::STRING8]
    ]);
  }

}
