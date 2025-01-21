<?php

namespace X11;

class QueryFontRequest extends Request {

  public function __construct($font) {
    $this->doRequest([
      ['opcode', 47, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['font', $font, Type::FONT]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}
