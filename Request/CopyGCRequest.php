<?php

namespace X11;

class CopyGCRequest extends Request {

  public function __construct($srcGc, $dstGc, $valueMask) {
    $this->sendRequest([
      ['opcode', 57, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4, Type::CARD16],
      ['srcGc', $srcGc, Type::GCONTEXT],
      ['dstGc', $dstGc, Type::GCONTEXT],
      ['valueMask', $valueMask, Type::BITMASK32, [
        'function', 'planeMask', 'foreground', 'background',
        'lineWidth', 'lineStyle', 'capStyle', 'joinStyle',
        'fillStyle', 'fillRule', 'tile', 'stipple',
        'tileStippleXOrigin', 'tileStippleYOrigin', 'font', 'subwindowMode',
        'graphicsExposures', 'clipXOrigin', 'clipYOrigin', 'clipMask',
        'dashOffset', 'dashes', 'arcMode',
      ]]
    ]);
  }

}
