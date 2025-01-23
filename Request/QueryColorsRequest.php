<?php

namespace X11;

class QueryColorsRequest extends Request {

  public function __construct($colormap, $pixels) {
    $data = [
      ['opcode', 91, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2 + count($pixels), Type::CARD16],
      ['cmap', $colormap, Type::COLORMAP],
    ];
    foreach ($pixels as $pixel) {
      $data[] = ['pixel', $pixel, Type::CARD32];
    }
    $this->sendRequest($data);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}

/*
  public static function QueryColors() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     2n                              reply length
     2     n                               number of RGBs in colors
     22                                    unused
     8n     LISTofRGB                      colors

  RGB
     2     CARD16                          red
     2     CARD16                          green
     2     CARD16                          blue
     2                                     unused
*/
  }

