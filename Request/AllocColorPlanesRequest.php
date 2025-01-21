<?php

namespace X11;

class AllocColorPlanesRequest extends Request {

  public function __construct($continguous, $colormap, $colors, $reds, $greens, $blues) {
    $this->sendRequest([
      ['opcode', 87, Type::BYTE],
      ['continguous', $continguous, Type::BOOL],
      ['requestLength', 4, Type::CARD16],
      ['colormap', $colormap, Type::COLORMAP],
      ['colors', $colors, Type::CARD16],
      ['reds', $reds, Type::CARD16],
      ['greens', $greens, Type::CARD16],
      ['bluess', $blues, Type::CARD16]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}

/*
  public static function AllocColorPlanes() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     n                               reply length
     2     n                               number of CARD32s in pixels
     2                                     unused
     4     CARD32                          red-mask
     4     CARD32                          green-mask
     4     CARD32                          blue-mask
     8                                     unused
     4n     LISTofCARD32                   pixels
*/
