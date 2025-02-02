<?php

namespace X11;

class GetAtomNameRequest extends Request {

  public function __construct($atom) {
    $opcode = 17;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['atom', Type::ATOM]
    ], $values);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    $response = $this->receiveResponse([
      ['reply', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['n', Type::CARD16],
      ['unused', Type::UNUSED, 22]
    ]);
    $name = $this->receiveResponse([
      ['name', Type::STRING8, $response['n']]
    ], false);
    $response['name'] = $name;
    return $response;
  }

}
