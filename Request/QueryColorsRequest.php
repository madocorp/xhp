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
    $this->doRequest($data);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}
