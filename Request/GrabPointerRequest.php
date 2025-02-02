<?php

namespace X11;

class GrabPointerRequest extends Request {

  public function __construct($ownerEvents, $grabWindow, $eventMask, $pointerMode, $keyboardMode, $confineTo, $cursor, $timestamp) {
    $opcode = 26;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['ownerEvents', Type::BOOL],
      ['requestLength', Type::CARD16],
      ['grabWindow', Type::WINDOW],
      ['eventMask', Type::BITMASK16, [false, false, 'ButtonPress', 'ButtonRelease', 'EnterWindow', 'LeaveWindow', 'PointerMotion', 'PointerMotionHint', 'Button1Motion', 'Button2Motion', 'Button3Motion', 'Button4Motion', 'Button5Motion', 'ButtonMotion', 'KeymapState']],
      ['pointerMode', Type::ENUM8, ['Synchronous', 'Asynchronous']],
      ['keyboardMode', Type::ENUM8, ['Synchronous', 'Asynchronous']],
      ['confineTo', Type::WINDOW],
      ['cursor', Type::CURSOR],
      ['timestamp', Type::CARD32]
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
    ]);;
  }

}
