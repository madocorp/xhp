<?php

namespace X11;

class GetAtomNameRequest extends Request {

  public function __construct($atom) {
    $this->sendRequest([
      ['opcode', 17, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['atom', $atom, Type::ATOM],
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    $response = $this->receiveResponse([
      ['reply', Type::BYTE],
      ['unused', Type::BYTE],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['n', Type::CARD16],
      ['unused', Type::STRING8, 22, false]
    ]);
    $name = $this->receiveResponse([
      ['name', Type::STRING8, $response['n']]
    ], false);
    $response['name'] = $name;
    return $response;
  }

}
