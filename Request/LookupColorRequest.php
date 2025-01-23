<?php

namespace X11;

class LookupColorRequest extends Request {

  public function __construct($colormap, $name) {
    $length = strlen($name);
    $pad = Connection::pad4($length);
    $this->sendRequest([
      ['opcode', 92, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3 + (($length + $pad) >> 2), Type::CARD16],
      ['cmap', $colormap, Type::COLORMAP],
      ['n', $length, Type::CARD16],
      ['unused', 0, Type::CARD16],
      ['name', $name, Type::STRING8],
      ['pad', $pad, Type::PAD4]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}

/*
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     0                               reply length
     2     CARD16                          exact-red
     2     CARD16                          exact-green
     2     CARD16                          exact-blue
     2     CARD16                          visual-red
     2     CARD16                          visual-green
     2     CARD16                          visual-blue
     12                                    unused
*/
