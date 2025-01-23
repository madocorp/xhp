<?php

namespace X11;

class QueryExtensionRequest extends Request {

  public function __construct($name) {
    $this->sendRequest([
      ['opcode', 98, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['length', strlen($name), Type::CARD16],
      ['unused', 0, Type::CARD16],
      ['name', $name, Type::STRING8]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return $this->receiveResponse([
      ['reply', Type::BYTE],
      ['unused', Type::BYTE],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['present', Type::BOOL],
      ['majorOpcode', Type::CARD8],
      ['firstEvent', Type::CARD8],
      ['firstError', Type::CARD8],
      ['unused', Type::STRING8, 20, false]
    ]);
  }

}

