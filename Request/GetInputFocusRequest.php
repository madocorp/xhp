<?php

namespace X11;

class GetInputFocusRequest extends Request {

  public function __construct() {
    $opcode = 43;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16]
    ], $values);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return $this->receiveResponse([
      ['reply', Type::BYTE],
      ['revertTo', Type::ENUM8, ['None', 'PointerRoor', 'Parent']],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['focus', Type::WINDOW],
      ['unused', Type::UNUSED, 20]
    ]);
  }

}
