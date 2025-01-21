<?php

namespace X11;

class CreateGCRequest extends Request {

  public function __construct($cid, $drawable, $values) {
    $this->sendRequest([
      ['opcode', 55, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4, Type::CARD16],
      ['cid', $cid, Type::GCONTEXT],
      ['drawable', $drawable, Type::DRAWABLE],
      ['values', $values, Type::VLIST, [
        ['function', Type::ENUM8, ['Clear', 'And', 'AndReverse', 'Copy', 'AddInverted', 'NoOp', 'Xor', 'Or', 'Nor', 'Equiv', 'Invert', 'OrReverse', 'CopyInverted', 'OrInverted', 'Nand', 'Set']],
        ['planeMask', Type::CARD32],
        ['foreground', Type::CARD32],
        ['background', Type::CARD32],
        ['lineWidth', Type::CARD16],
        ['lineStyle', Type::ENUM8, ['Solid', 'OnOffDash', 'DoubleDash']],
        ['capStyle', Type::ENUM8, ['NotLast', 'Butt', 'Round', 'Projecting']],
        ['joinStyle', Type::ENUM8, ['Mitter', 'Round', 'Bevel']],
        ['fillStyle', Type::ENUM8, ['Solid', 'Tiled', 'Stippled', 'OpaqueStippled']],
        ['fillRule', Type::ENUM8, ['EvenOdd', 'Winding']],
        ['tile', Type::PIXMAP],
        ['stipple', Type::PIXMAP],
        ['tileStippleXOrigin', Type::INT16],
        ['tileStippleYOrigin', Type::INT16],
        ['font', Type::FONT],
        ['subwindowMode', Type::ENUM8, ['ClipByChildren', 'IncludeInferiors']],
        ['graphicsExposures', Type::BOOL],
        ['clipXOrigin', Type::INT16],
        ['clipYOrigin', Type::INT16],
        ['clipMask', Type::PIXMAP],
        ['dashOffset', Type::CARD16],
        ['dashes', Type::CARD8],
        ['arcMode', Type::ENUM8, ['Chord', 'PieSlice']]
      ]]
    ]);
  }

}
