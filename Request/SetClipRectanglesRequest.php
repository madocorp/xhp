<?php

namespace X11;

class SetClipRectanglesRequest extends Request {

  public function __construct($ordering, $gc, $clipXOrigin, $clipYOrigin, $rectangles) {
    $data = [
      ['opcode', 59, Type::BYTE],
      ['ordering', $ordering, Type::ENUM8, ['UnSorted', 'YSorted', 'YXSorted', 'YXBanded']],
      ['requestLength', 3 + 2 * count($rectangles), Type::CARD16],
      ['gc', $gc, Type::GCCONTEXT],
      ['clipXOrigin', $clipXOrigin, Type::INT16],
      ['clipYOrigin', $clipYOrigin, Type::INT16],
      ['dashes', $dashes, Type::STRING8]
    ];
    foreach ($rectangles as $rectangle) {
      $data[] = ['x', $rectangle['x'], Type::INT16];
      $data[] = ['y', $rectangle['y'], Type::INT16];
      $data[] = ['width', $rectangle['width'], Type::CARD16];
      $data[] = ['height', $rectangle['height'], Type::CARD16];
    }
    $this->doRequest($data);
  }

  protected function processResponse() {
    return false;
  }

}
