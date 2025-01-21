<?php

namespace X11;

class ListInstalledColormapsRequest extends Request {

  public function __construct($window) {
    $this->sendRequest([
      ['opcode', 83, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['window', $window, Type::WINDOW]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}

/*
  public static function ListInstalledColormaps() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     n                               reply length
     2     n                               number of COLORMAPs in cmaps
     22                                    unused
     4n     LISTofCOLORMAP                 cmaps
*/
