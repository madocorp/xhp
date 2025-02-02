<?php

namespace X11;

class ChangeWindowAttributesRequest extends Request {

  public function __construct($window, $values) {
    $opcode = 2;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['window', Type::WINDOW],
      ['values', Type::VLIST, [
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
    ], $values);
  }

}
