<?php

namespace X11;

class QueryKeymapRequest extends Request {

  public function __construct() {
    $opcode = 44;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16]
    ], $values);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    $response = $this->receiveResponse([
      ['reply', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['keys', Type::STRING8, 32, false]
    ]);
    $response['keys'] = array_values(unpack('C32', $response['keys']));
    return $response;
  }

}
