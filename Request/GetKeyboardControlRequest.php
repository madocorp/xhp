<?php

namespace X11;

class GetKeyboardControlRequest extends Request {

  public function __construct() {
    $opcode = 103;
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
      ['globalAutoRepeat', Type::ENUM8, ['Off', 'On']],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['ledMask', Type::CARD32],
      ['keyClickPercent', Type::CARD8],
      ['bellPercent', Type::CARD8],
      ['bellPitch', Type::CARD16],
      ['bellDuration', Type::CARD16],
      ['unused', Type::UNUSED, 2],
      ['autoRepeats', Type::STRING8, 32, false]
    ]);
  }

}
