<?php

namespace X11;

class EnableBigRequests extends Request {

  public function __construct($opcode) {
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
    ], $values);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    $response = $this->receiveResponse([
      ['reply', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['maximumRequestLength', Type::CARD32],
      ['unused', Type::UNUSED, 20]
    ]);
    return $response;
  }

}

