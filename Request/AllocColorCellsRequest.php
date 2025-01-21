<?php

namespace X11;

class AllocColorCellsRequest extends Request {

  public function __construct($continguous, $colormap, $colors, $planes) {
    $this->sendRequest([
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

/*
  public static function AllocColorCells() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     n+m                             reply length
     2     n                               number of CARD32s in pixels
     2     m                               number of CARD32s in masks
     20                                    unused
     4n     LISTofCARD32                   pixels
     4m     LISTofCARD32                   masks
*/
