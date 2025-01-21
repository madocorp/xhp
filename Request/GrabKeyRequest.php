<?php

namespace X11;

class GrabKeyRequest extends Request {

  public function __construct($ownerEvents, $grabWindow, $modifiers, $key, $pointerMode, $keyboardMode) {
    $this->sendRequest([
      ['opcode', 33, Type::BYTE],
      ['ownerEvents', $ownerEvents, Type::BOOL],
      ['requestLength', 4, Type::CARD16],
      ['grabWindow', $grabWindow, Type::WINDOW],
      ['modifiers', $modifiers, Type::CARD16],
      ['key', $key, Type::BYTE],
      ['pointerMode', $pointerMode, Type::ENUM8, ['Synchronous', 'Asynchronous']],
      ['keyboardMode', $pointerMode, Type::ENUM8, ['Synchronous', 'Asynchronous']],
      ['unused', 0, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['unused', 0, Type::BYTE]
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
