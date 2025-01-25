<?php

namespace X11;

class GetSelectionOwnerRequest extends Request {

  public function __construct($selection) {
    $this->sendRequest([
      ['opcode', 23, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['selection', $selection, Type::ATOM]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return $this->receiveResponse([
      ['reply', Type::BYTE],
      ['unused', Type::BYTE],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['owner', Type::WINDOW],
      ['unused', Type::STRING8, 20, false],
    ]);
  }

}

