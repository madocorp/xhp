<?php

namespace X11;

class SetCloseDownModeRequest extends Request {

  public function __construct($mode) {
    $this->sendRequest([
      ['opcode', 112, Type::BYTE],
      ['mode', $mode, Type::ENUM8, ['Destroy', 'RetainPermanent', 'RetainTemporary']],
      ['requestLength', 1, Type::CARD16]
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
