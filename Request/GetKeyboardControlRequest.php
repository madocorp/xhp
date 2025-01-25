<?php

namespace X11;

class GetKeyboardControlRequest extends Request {

  public function __construct() {
    $this->sendRequest([
      ['opcode', 103, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 1, Type::CARD16],
    ]);
    Connection::setResponse($this->processResponse());
  }


  protected function processResponse() {
    return $this->receiveResponse([
      ['reply', Type::BYTE],
      ['globalAutoRepeat', Type::ENUM8, ['Off', 'On']],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['ledMask', Type::CARD32],
      ['keyClickPercent', Type::CARD8],
      ['bellPercent', Type::CARD8],
      ['bellPitch', Type::CARD16],
      ['bellDuration', Type::CARD16],
      ['unused', Type::CARD16],
      ['autoRepeats', Type::STRING8, 32, false]
    ]);
  }

}
