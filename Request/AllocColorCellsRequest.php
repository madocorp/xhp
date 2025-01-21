<?php

namespace X11;

class AllocColorCellsRequest extends Request {

  public function __construct($continguous, $colormap, $colors, $planes) {
    $this->doRequest([
      ['opcode', 86, Type::BYTE],
      ['continguous', $continguous, Type::BOOL],
      ['requestLength', 3, Type::CARD16],
      ['colormap', $colormap, Type::COLORMAP],
      ['colors', $colors, Type::CARD16],
      ['planes', $planes, Type::CARD16]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}
