<?php

namespace X11;

class SetClipRectanglesRequest extends Request {

  public function __construct($ordering, $gc, $clipXOrigin, $clipYOrigin, $rectangles) {
    $opcode = 59;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['ordering', Type::ENUM8, ['UnSorted', 'YSorted', 'YXSorted', 'YXBanded']],
      ['requestLength', Type::CARD16],
      ['gc', Type::GCONTEXT],
      ['clipXOrigin', Type::INT16],
      ['clipYOrigin', Type::INT16],
      ['rectangles', Type::FLIST, [
        ['x', Type::INT16],
        ['y', Type::INT16],
        ['width', Type::CARD16],
        ['height', Type::CARD16]
      ]]
    ], $values);
  }

}
