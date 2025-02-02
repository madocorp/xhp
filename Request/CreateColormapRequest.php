<?php

namespace X11;

class CreateColormapRequest extends Request {

  public function __construct($alloc, $colormap, $window, $visual) {
    $opcode = 78;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['alloc', Type::ENUM8, ['None', 'All']],
      ['requestLength', Type::CARD16],
      ['colormap', Type::COLORMAP],
      ['window', Type::WINDOW],
      ['visual', Type::VISUALID]
    ], $values);
  }

}
