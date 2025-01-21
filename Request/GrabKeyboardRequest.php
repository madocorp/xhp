<?php

namespace X11;

class GrabKeyboardRequest extends Request {

  public function __construct($ownerEvents, $grabWindow, $timestamp, $pointerMode, $keyboardMode) {
    $this->doRequest([
      ['opcode', 31, Type::BYTE],
      ['ownerEvents', $ownerEvents, Type::BOOL],
      ['requestLength', 4, Type::CARD16],
      ['grabWindow', $grabWindow, Type::WINDOW],
      ['timestamp', $timestamp, Type::CARD32],
      ['pointerMode', $pointerMode, Type::ENUM8, ['Synchronous', 'Asynchronous']],
      ['keyboardMode', $pointerMode, Type::ENUM8, ['Synchronous', 'Asynchronous']],
      ['unused', 0, Type::BYTE]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}
