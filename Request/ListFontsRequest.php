<?php

namespace X11;

class ListFontsRequest extends Request {

  public function __construct($maxNames, $pattern) {
    $length = strlen($pattern);
    $this->sendRequest([
      ['opcode', 49, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['maxNames', $maxNames, Type::CARD16],
      ['lengthOfPattern', $length, Type::PAD4],
      ['pattern', $pattern, Type::STRING8],
      ['pad', Connection::pad4($length), Type::PAD4]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}

/*
  public static function ListFonts() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     (n+p)/4                         reply length
     2     CARD16                          number of STRs in names
     22                                    unused
     n     LISTofSTR                       names
     p                                     unused, p=pad(n)
*/
