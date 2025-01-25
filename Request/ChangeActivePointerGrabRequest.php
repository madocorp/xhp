<?php

namespace X11;

class ChangeActivePointerGrabRequest extends Request {

  public function __construct($cursor, $timestamp, $eventMask) {
    $this->sendRequest([
      ['opcode', 30, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4, Type::CARD16],
      ['cursor', $cursor, Type::CURSOR],
      ['timestamp', $timestamp, Type::CARD32],
      ['eventMask', $eventMask, Type::BITMASK16, [false, false, 'ButtonPress', 'ButtonRelease', 'EnterWindow', 'LeaveWindow', 'PointerMotion', 'PointerMotionHint', 'Button1Motion', 'Button2Motion', 'Button3Motion', 'Button4Motion', 'Button5Motion', 'ButtonMotion', 'KeymapState']],
      ['unused', 0, Type::CARD16]
    ]);
  }

}

