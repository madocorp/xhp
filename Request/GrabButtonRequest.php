<?php

namespace X11;

class GrabButtonRequest extends Request {

  public function __construct($ownerEvents, $grabWindow, $eventMask, $pointerMode, $keyboardMode, $confineTo, $cursor, $button, $modifiers) {
    $this->sendRequest([
      ['opcode', 28, Type::BYTE],
      ['ownerEvents', $ownerEvents, Type::BOOL],
      ['requestLength', 6, Type::CARD16],
      ['grabWindow', $grabWindow, Type::WINDOW],
      ['EventMask', $eventMask, Type::CARD16],
      ['pointerMode', $pointerMode, Type::ENUM8, ['Synchronous', 'Asynchronous']],
      ['keyboardMode', $pointerMode, Type::ENUM8, ['Synchronous', 'Asynchronous']],
      ['confineTo', $confineTo, Type::WINDOW],
      ['cursor', $cursor, Type::CURSOR],
      ['button', $button, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['modifiers', $modifiers, Type::CARD16]
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
