<?php

namespace X11;

class ChangeWindowAttributesRequest extends Request {

  public function __construct($window, $values) {
    $this->sendRequest([
      ['opcode', 2, Type::BYTE],
      ['unuset', 0, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['values', $values, Type::VLIST, [
        ['backgroundPixmap', Type::PIXMAP],
        ['backgroundPixel', Type::CARD32],
        ['borderPixmap', Type::PIXMAP],
        ['borderPixel', Type::CARD32],
        ['bitGravity', Type::BYTE],
        ['winGravity', Type::BYTE],
        ['backingStore', Type::ENUM8, ['NotUseful', 'WhenMapped', 'Always']],
        ['backingPlanes', Type::CARD32],
        ['backingPixel', Type::CARD32],
        ['overrideRedirect', Type::BYTE],
        ['saveUnder', Type::BYTE],
        ['eventMask', Type::CARD32],
        ['doNotPropagateMask', Type::CARD32],
        ['colormap', Type::COLORMAP],
        ['cursor', Type::CURSOR]
      ]]
    ]);
  }

}
