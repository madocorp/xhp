<?php

namespace X11;

class CreateColormapRequest extends Request {

  public function __construct($alloc, $colormap, $window, $visual) {
    $this->sendRequest([
      ['opcode', 78, Type::BYTE],
      ['alloc', $alloc, Type::ENUM8, ['None', 'All']],
      ['requestLength', 4, Type::CARD16],
      ['colormap', $colormap, Type::COLORMAP],
      ['window', $window, Type::WINDOW],
      ['visual', $visual, Type::VISUALID]
    ]);
  }

}
