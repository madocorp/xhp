<?php

namespace X11;

class GetInputFocusRequest extends Request {

  public function __construct() {
    $this->sendRequest([
      ['opcode', 43, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 1, Type::CARD16]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return $this->receiveResponse([
      ['reply', Type::BYTE],
      ['revertTo', Type::ENUM8, ['None', 'PointerRoor', 'Parent']],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['focus', Type::WINDOW],
      ['unused', Type::STRING8, 20, false]
    ]);
  }

}
