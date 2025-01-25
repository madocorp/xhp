<?php

namespace X11;

class AllocColorRequest extends Request {

  public function __construct($colormap, $red, $green, $blue) {
    $this->sendRequest([
      ['opcode', 84, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4, Type::CARD16],
      ['colormap', $colormap, Type::COLORMAP],
      ['red', $red, Type::CARD16],
      ['green', $green, Type::CARD16],
      ['blue', $blue, Type::CARD16],
      ['unused', 0, Type::CARD16],
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return $this->receiveResponse([
      ['reply', Type::BYTE],
      ['unused', Type::BYTE],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['red', Type::CARD16],
      ['green', Type::CARD16],
      ['blue', Type::CARD16],
      ['unused', Type::CARD16],
      ['pixel', Type::CARD32],
      ['unused', Type::STRING8, 12, false]
    ]);
  }

}

