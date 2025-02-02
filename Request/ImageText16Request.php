<?php

namespace X11;

class ImageText16Request extends Request {

  public function __construct($drawable, $gc, $x, $y, $string) {
    $length = mb_strlen($string);
    $string = mb_convert_encoding($string, "ISO-10646-UCS-2", "UTF-8");
    $opcode = 77;
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

