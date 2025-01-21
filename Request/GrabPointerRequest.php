<?php

namespace X11;

class GrabPointerRequest extends Request {

  public function __construct(
    $grabWindow, $eventMask, $pointerMode, $keyboardMode,
    $confineTo, $cursor, $timestamp
  ) {
    $this->doRequest([
      ['opcode', 26, Type::BYTE],
      ['ownerEvents', 0, Type::BOOL],
      ['requestLength', 6, Type::CARD16],
      ['grabWindow', $grabWindow, Type::WINDOW],
      ['eventMask', $eventMask, Type::CARD16],
      ['pointerMode', $pointerMode, Type::ENUM8, ['Synchronous', 'Asynchronous']],
      ['keyboardMode', $keyboardMode, Type::ENUM8, ['Synchronous', 'Asynchronous']],
      ['confineTo', $confineTo, Type::WINDOW],
      ['cursor', $cursor, Type::CURSOR],
      ['timestamp', $timestamp, Type::CARD32]
    ]);
    return Response::GrabKeyboard();
  }


  protected function processResponse() {
    return false;
  }

}
