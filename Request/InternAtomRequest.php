<?php

namespace X11;

class InternAtomRequest extends Request {

  public function __construct($onlyIfExists, $name) {
    $opcode = 16;
    $n = strlen($name);
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['onlyIfExists', Type::BOOL],
      ['requestLength', Type::CARD16],
      ['n', Type::CARD16],
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
      ['atom', Type::ATOM],
      ['unused', Type::UNUSED, 20]
    ]);
  }

}
