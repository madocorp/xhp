<?php

namespace X11;

class GetScreenSaverRequest extends Request {

  public function __construct() {
    $opcode = 108;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
    ], $values);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return $this->receiveResponse([
      ['reply', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['timeout', Type::CARD16],
      ['interval', Type::CARD16],
      ['preferBlanking', Type::ENUM8, ['No', 'Yes']],
      ['allowExposures', Type::ENUM8, ['No', 'Yes']],
      ['unused', Type::UNUSED, 18]
    ]);
  }

}
