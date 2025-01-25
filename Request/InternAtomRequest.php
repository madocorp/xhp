<?php

namespace X11;

class InternAtomRequest extends Request {

  public function __construct($ifExists, $name) {
    $this->sendRequest([
      ['opcode', 16, Type::BYTE],
      ['onlyIfExists', $ifExists, Type::BOOL],
      ['requestLength', 2, Type::CARD16],
      ['n', strlen($name), Type::CARD16],
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
      ['atom', Type::ATOM],
      ['unused', Type::STRING8, 20, false]
    ]);
  }

}
