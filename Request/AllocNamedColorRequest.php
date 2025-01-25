<?php

namespace X11;

class AllocNamedColorRequest extends Request {

  public function __construct($colormap, $name) {
    $length = strlen($name);
    $this->sendRequest([
      ['opcode', 85, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['colormap', $colormap, Type::COLORMAP],
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
      ['pixel', Type::CARD32],
      ['exactRed', Type::CARD16],
      ['exactGreen', Type::CARD16],
      ['exactBlue', Type::CARD16],
      ['visualRed', Type::CARD16],
      ['visualGreen', Type::CARD16],
      ['visualBlue', Type::CARD16],
      ['unused', Type::STRING8, 8, false]
    ]);

  }

}
