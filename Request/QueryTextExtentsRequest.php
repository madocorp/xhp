<?php

namespace X11;

class QueryTextExtentsRequest extends Request {

  public function __construct($font, $string) {
    $length = strlen($string);
    $pad = Connection::pad4($length);
    $this->sendRequest([
      ['opcode', 48, Type::BYTE],
      ['oddLength', $pad == 2, Type::BOOL],
      ['requestLength', 2, Type::CARD16],
      ['font', $font, Type::FONT],
      ['string', $string, Type::STRING8],
      ['pad', $pad, Type::PAD4]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}
