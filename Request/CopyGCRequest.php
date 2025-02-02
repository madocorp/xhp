<?php

namespace X11;

class CopyGCRequest extends Request {

  public function __construct($srcGc, $dstGc, $valueMask) {
    $opcode = 57;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['srcGc', Type::GCONTEXT],
      ['dstGc', Type::GCONTEXT],
      ['valueMask', Type::BITMASK32, [
        'function', 'planeMask', 'foreground', 'background',
        'lineWidth', 'lineStyle', 'capStyle', 'joinStyle',
        'fillStyle', 'fillRule', 'tile', 'stipple',
        'tileStippleXOrigin', 'tileStippleYOrigin', 'font', 'subwindowMode',
        'graphicsExposures', 'clipXOrigin', 'clipYOrigin', 'clipMask',
        'dashOffset', 'dashes', 'arcMode',
      ]]
    ], $values);
  }

}
