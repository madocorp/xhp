<?php

namespace X11;

class QueryPointerRequest extends Request {

  public function __construct($window) {
    $opcode = 38;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['window', Type::WINDOW]
    ], $values);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return $this->receiveResponse([
      ['reply', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['root', Type::WINDOW],
      ['child', Type::WINDOW],
      ['rootX', Type::INT16],
      ['rootY', Type::INT16],
      ['winX', Type::INT16],
      ['winY', Type::INT16],
      ['mask', Type::INT16],
      ['unused', Type::UNUSED, 6]
    ]);
  }

}

