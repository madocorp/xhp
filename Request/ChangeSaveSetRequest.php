<?php

namespace X11;

class ChangeSaveSetRequest extends Request {

  public function __construct($mode, $window) {
    $this->sendRequest([
      ['opcode', 6, Type::BYTE],
      ['mode', $mode, Type::ENUM8, ['Insert', 'Delete']],
      ['requestLength', 2, Type::CARD16],
      ['window', $window, Type::WINDOW]
    ]);
  }

}
