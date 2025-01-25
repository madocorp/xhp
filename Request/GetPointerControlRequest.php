<?php

namespace X11;

class GetPointerControlRequest extends Request {

  public function __construct() {
    $this->sendRequest([
      ['opcode', 106, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 1, Type::CARD16],
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return $this->receiveResponse([
      ['reply', Type::BYTE],
      ['unused', Type::BYTE],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['accelerationNumerator', Type::CARD16],
      ['accelerationDenominator', Type::CARD16],
      ['thresold', Type::CARD16],
      ['unused', Type::STRING8, 18, false]
    ]);

  }

}
