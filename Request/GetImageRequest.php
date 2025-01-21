<?php

namespace X11;

class GetImageRequest extends Request {

  public function __construct($format, $drawable, $x, $y, $width, $height, $planeMask) {
    $this->sendRequest([
      ['opcode', 73, Type::BYTE],
      ['format', $format, Type::ENUM8, ['XYPixmap', 'ZPixmap']],
      ['requestLength', 2, Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['x', $x, Type::INT16],
      ['y', $y, Type::INT16],
      ['width', $width, Type::CARD16],
      ['height', $height, Type::CARD16],
      ['planeMask', $planeMask, Type::CARD32]
    ]);
    Connection::setResponse($this->processResponse());
  }


  public function PolyText8($drawable, $gc, $texts) {
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

  public function PolyText16() {
/*
     1     75                              opcode
     1                                     unused
     2     4+(n+p)/4                       request length
     4     DRAWABLE                        drawable
     4     GCONTEXT                        gc
     2     INT16                           x
     2     INT16                           y
     n     LISTofTEXTITEM16                items
     p                                     unused, p=pad(n)  (p must be 0 or
                                           1)

  TEXTITEM16
     1     m                               number of CHAR2Bs in string
                                           (cannot be 255)
     1     INT8                            delta
     2m     STRING16                       string
  or
     1     255                             font-shift indicator
     1                                     font byte 3 (most-significant)
     1                                     font byte 2
     1                                     font byte 1
     1                                     font byte 0 (least-significant)
*/
  }


  public function ImageText8($drawable, $gc, $x, $y, $string) {
    $this->sendRequest([
      ['opcode', 76, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4, Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCONTEXT],
      ['x', $x, Type::INT16],
      ['y', $y, Type::INT16],
      ['string', $string, Type::STRING8],
      ['pad', Connection::pad4(strlen($string)), Type::PAD4]
    ]);
  }

  public function ImageText16($drawable, $gc, $x, $y, $string) {
    $this->sendRequest([
      ['opcode', 77, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4, Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE],
      ['gc', $gc, Type::GCONTEXT],
      ['x', $x, Type::INT16],
      ['y', $y, Type::INT16],
      ['string', $string, Type::STRING8],
      ['pad', Connection::pad4(strlen($string)), Type::PAD4]
    ]);
  }

  protected function processResponse() {
    return false;
  }

}

/*
  public static function GetImage() {
▶
     1     1                               Reply
     1     CARD8                           depth
     2     CARD16                          sequence number
     4     (n+p)/4                         reply length
     4     VISUALID                        visual
          0     None
     20                                    unused
     n     LISTofBYTE                      data
     p                                     unused, p=pad(n)
*/
