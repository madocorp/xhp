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
