<?php

namespace X11;

class AllocColorRequest extends Request {

  public function __construct($colormap, $red, $green, $blue) {
    $this->sendRequest([
      ['opcode', 84, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4, Type::CARD16],
      ['colormap', $colormap, Type::COLORMAP],
      ['red', $red, Type::CARD16],
      ['green', $green, Type::CARD16],
      ['blue', $blue, Type::CARD16],
      ['unused', 0, Type::CARD16],
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}

/*
  public static function AllocColor() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     0                               reply length
     2     CARD16                          red
     2     CARD16                          green
     2     CARD16                          blue
     2                                     unused
     4     CARD32                          pixel
     12                                    unused
*/
