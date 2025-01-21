<?php

namespace X11;

class ChangeActivePointerGrabRequest extends Request {

  public function __construct($cursor, $timestamp, $eventMask) {
    $this->doRequest([
      ['opcode', 30, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4, Type::CARD16],
      ['cursor', $cursor, Type::CURSOR],
      ['timestamp', $timestamp, Type::CARD32],
      ['eventMask', $eventMask, Type::CARD16],
      ['unused', 0, Type::CARD16]
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
