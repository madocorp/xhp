<?php

namespace X11;

class AllocNamedColorRequest extends Request {

  public function __construct($name) {
    $length = strlen($name);
    $this->sendRequest([
      ['opcode', 85, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['length', $length, Type::CARD16],
      ['unused', 0, Type::CARD16],
      ['name', $name, Type::STRING8],
      ['pad', Connection::pad4($length), Type::PAD4],
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}

/*
  public static function AllocNamedColor() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     0                               reply length
     4     CARD32                          pixel
     2     CARD16                          exact-red
     2     CARD16                          exact-green
     2     CARD16                          exact-blue
     2     CARD16                          visual-red
     2     CARD16                          visual-green
     2     CARD16                          visual-blue
     8                                     unused
*/
