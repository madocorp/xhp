<?php

namespace X11;

class AllocColorRequest extends Request {

  public function __construct($colormap, $red, $green, $blue) {
    $opcode = 84;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['colormap', Type::COLORMAP],
      ['red', Type::CARD16],
      ['green', Type::CARD16],
      ['blue', Type::CARD16],
      ['unused', Type::UNUSED, 2],
    ], $values);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return $this->receiveResponse([
      ['reply', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['red', Type::CARD16],
      ['green', Type::CARD16],
      ['blue', Type::CARD16],
      ['unused', Type::CARD16],
      ['pixel', Type::CARD32],
      ['unused', Type::UNUSED, 12]
    ]);
  }

}

