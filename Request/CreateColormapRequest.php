<?php

namespace X11;

class CreateColormapRequest extends Request {

  public function __construct($alloc, $window, $visual) {
    $this->doRequest([
      ['opcode', 78, Type::BYTE],
      ['alloc', 0, Type::ENUM8, ['None', 'All']],
      ['requestLength', 4, Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['visual', $visual, Type::VISUALID],
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
