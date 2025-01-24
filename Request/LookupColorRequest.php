<?php

namespace X11;

class LookupColorRequest extends Request {

  public function __construct($colormap, $name) {
    $this->sendRequest([
      ['opcode', 92, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['cmap', $colormap, Type::COLORMAP],
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
      ['exactRed', Type::CARD16],
      ['exactGreen', Type::CARD16],
      ['exactBlue', Type::CARD16],
      ['visualRed', Type::CARD16],
      ['visualGreen', Type::CARD16],
      ['visualBlue', Type::CARD16],
      ['unused', Type::STRING8, 12, false]
    ]);
  }

}
