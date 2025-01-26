<?php

namespace X11;

class GrabKeyboardRequest extends Request {

  public function __construct($ownerEvents, $grabWindow, $timestamp, $pointerMode, $keyboardMode) {
    $this->sendRequest([
      ['opcode', 31, Type::BYTE],
      ['ownerEvents', $ownerEvents, Type::BOOL],
      ['requestLength', 4, Type::CARD16],
      ['grabWindow', $grabWindow, Type::WINDOW],
      ['timestamp', $timestamp, Type::CARD32],
      ['pointerMode', $pointerMode, Type::ENUM8, ['Synchronous', 'Asynchronous']],
      ['keyboardMode', $pointerMode, Type::ENUM8, ['Synchronous', 'Asynchronous']],
      ['unused', 0, Type::CARD16]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return $this->receiveResponse([
      ['reply', Type::BYTE],
      ['status', Type::ENUM8, ['Success', 'AlreadyGrabbed', 'InvalidTime', 'NotViewable', 'Frozen']],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['unused', Type::STRING8, 24, false]
    ]);
  }

}
