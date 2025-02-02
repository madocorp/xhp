<?php

namespace X11;

class GetPointerControlRequest extends Request {

  public function __construct() {
    $opcode = 106;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
    ], $values);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return $this->receiveResponse([
      ['reply', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['accelerationNumerator', Type::CARD16],
      ['accelerationDenominator', Type::CARD16],
      ['thresold', Type::CARD16],
      ['unused', Type::UNUSED, 18]
    ]);

  }

}
