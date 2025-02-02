<?php

namespace X11;

class GetSelectionOwnerRequest extends Request {

  public function __construct($selection) {
    $opcode = 23;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['selection', Type::ATOM]
    ], $values);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return $this->receiveResponse([
      ['reply', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['owner', Type::WINDOW],
      ['unused', Type::UNUSED, 20],
    ]);
  }

}

