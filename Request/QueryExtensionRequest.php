<?php

namespace X11;

class QueryExtensionRequest extends Request {

  public function __construct($name) {
    $length = strlen($name);
    $opcode = 98;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['length', Type::CARD16],
      ['unused', Type::UNUSED, 2],
      ['name', Type::STRING8]
    ], $values);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return $this->receiveResponse([
      ['reply', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['present', Type::BOOL],
      ['majorOpcode', Type::CARD8],
      ['firstEvent', Type::CARD8],
      ['firstError', Type::CARD8],
      ['unused', Type::UNUSED, 20]
    ]);
  }

}

