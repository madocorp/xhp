<?php

namespace X11;

class GrabPointerRequest extends Request {

  public function __construct(
    $ownerEvents, $grabWindow, $eventMask, $pointerMode,
    $keyboardMode, $confineTo, $cursor, $timestamp
  ) {
    $this->sendRequest([
      ['opcode', 26, Type::BYTE],
      ['ownerEvents', $ownerEvents, Type::BOOL],
      ['requestLength', 6, Type::CARD16],
      ['grabWindow', $grabWindow, Type::WINDOW],
      ['eventMask', $eventMask, Type::BITMASK16, [false, false, 'ButtonPress', 'ButtonRelease', 'EnterWindow', 'LeaveWindow', 'PointerMotion', 'PointerMotionHint', 'Button1Motion', 'Button2Motion', 'Button3Motion', 'Button4Motion', 'Button5Motion', 'ButtonMotion', 'KeymapState']],
      ['pointerMode', $pointerMode, Type::ENUM8, ['Synchronous', 'Asynchronous']],
      ['keyboardMode', $keyboardMode, Type::ENUM8, ['Synchronous', 'Asynchronous']],
      ['confineTo', $confineTo, Type::WINDOW],
      ['cursor', $cursor, Type::CURSOR],
      ['timestamp', $timestamp, Type::CARD32]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    $response = $this->receiveResponse([
      ['reply', Type::BYTE],
      ['status', Type::ENUM8, ['Success', 'AlreadyGrabbed', 'InvalidTime', 'NotViewable', 'Frozen']],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['unused', Type::STRING8, 24, false]
    ]);
    return false;
  }

}
