<?php

namespace X11;

class AllocColorPlanesRequest extends Request {

  public function __construct($continguous, $colormap, $colors, $reds, $greens, $blues) {
    $this->doRequest([
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
