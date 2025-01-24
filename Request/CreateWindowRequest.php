<?php

namespace X11;

class CreateWindowRequest extends Request {

  public function __construct(
    $depth, $wid, $parent, $x,
    $y, $width, $height, $borderWidth,
    $class, $visual, $values
  ) {
    $this->sendRequest([
      ['opcode', 1, Type::BYTE],
      ['depth', $depth, Type::CARD8],
      ['requestLength', 8, Type::CARD16],
      ['wid', $wid, Type::WINDOW],
      ['parent', $parent, Type::WINDOW],
      ['x', $x, Type::INT16],
      ['y', $y, Type::INT16],
      ['width', $width, Type::CARD16],
      ['height', $height, Type::CARD16],
      ['borderWidth', $borderWidth, Type::CARD16],
      ['class', $class, Type::ENUM16, ['CopyFromParent', 'InputOutput', 'InputOnly']],
      ['visual', $visual, Type::VISUALID],
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
        ['eventMask', Type::BITMASK32, ['KeyPress', 'KeyRelease', 'ButtonPress', 'ButtonRelease', 'EnterWindow', 'LeaveWindow', 'PointerMotion', 'PointerMotionHint', 'Button1Motion', 'Button2Motion', 'Button3Motion', 'Button4Motion', 'Button5Motion', 'ButtonMotion', 'KeymapState', 'Exposure', 'VisibilityChange', 'StructureNotify', 'ResizeRedirect', 'SubstructureNotify', 'SubstructureRedirect', 'FocusChange', 'PropertyChange', 'ColormapChange', 'OwnerGrabButton']],
        ['doNotPropagateMask', Type::CARD32],
        ['colormap', Type::CARD32],
        ['cursor', Type::CARD32]
      ]]
    ]);
  }

}
