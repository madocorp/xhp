<?php

namespace X11;

class QueryPointerRequest extends Request {

  public function __construct($window) {
    $this->sendRequest([
      ['opcode', 38, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['window', $window, Type::WINDOW]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return $this->receiveResponse([
      ['reply', Type::BYTE],
      ['unused', Type::BYTE],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['root', Type::WINDOW],
      ['child', Type::WINDOW],
      ['rootX', Type::INT16],
      ['rootY', Type::INT16],
      ['winX', Type::INT16],
      ['winY', Type::INT16],
      ['mask', Type::INT16],
      ['unused', Type::STRING8, 6, false]
    ]);
  }

}

