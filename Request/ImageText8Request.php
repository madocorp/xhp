<?php

namespace X11;

class ImageText8Request extends Request {

  public function __construct($drawable, $gc, $x, $y, $string) {
    $length = strlen($string);
    $opcode = 76;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['length', Type::CARD8],
      ['requestLength', Type::CARD16],
      ['drawable', Type::DRAWABLE],
      ['gc', Type::GCONTEXT],
      ['x', Type::INT16],
      ['y', Type::INT16],
      ['string', Type::STRING8]
    ], $values);
  }

}
