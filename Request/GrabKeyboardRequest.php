<?php

namespace X11;

class GrabKeyboardRequest extends Request {

  public function __construct($ownerEvents, $grabWindow, $timestamp, $pointerMode, $keyboardMode) {
    $opcode = 31;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['ownerEvents', Type::BOOL],
      ['requestLength', Type::CARD16],
      ['grabWindow', Type::WINDOW],
      ['timestamp', Type::CARD32],
      ['pointerMode', Type::ENUM8, ['Synchronous', 'Asynchronous']],
      ['keyboardMode', Type::ENUM8, ['Synchronous', 'Asynchronous']],
      ['unused', Type::UNUSED, 2]
    ], $values);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return $this->receiveResponse([
      ['reply', Type::BYTE],
      ['status', Type::ENUM8, ['Success', 'AlreadyGrabbed', 'InvalidTime', 'NotViewable', 'Frozen']],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['unused', Type::UNUSED, 24]
    ]);
  }

}
