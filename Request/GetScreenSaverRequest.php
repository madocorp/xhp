<?php

namespace X11;

class GetScreenSaverRequest extends Request {

  public function __construct() {
    $this->sendRequest([
      ['opcode', 108, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 1, Type::CARD16],
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return $this->receiveResponse([
      ['reply', Type::BYTE],
      ['unused', Type::BYTE],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['timeout', Type::CARD16],
      ['interval', Type::CARD16],
      ['preferBlanking', Type::ENUM8, ['No', 'Yes']],
      ['allowExposures', Type::ENUM8, ['No', 'Yes']],
      ['unused', Type::STRING8, 18, false]
    ]);
  }

}
