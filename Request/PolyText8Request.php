<?php

namespace X11;

class PolyText8Request extends Request {

  public function __construct($drawable, $gc, $texts) {
    $this->sendRequest([
      ['opcode', 74, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4, Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCONTEXT],
      ['x', $x, Type::INT16],
      ['y', $y, Type::INT16]
//???
/*
     n     LISTofTEXTITEM8                 items
     p                                     unused, p=pad(n)  (p is always 0
                                           or 1)

  TEXTITEM8
     1     m                               length of string (cannot be 255)
     1     INT8                            delta
     m     STRING8                         string
  or
     1     255                             font-shift indicator
     1                                     font byte 3 (most-significant)
     1                                     font byte 2
     1                                     font byte 1
     1                                     font byte 0 (least-significant)
*/
    ]);
  }

}