<?php

namespace X11;

class AllocNamedColorRequest extends Request {

  public function __construct($colormap, $name) {
    $length = strlen($name);
    $opcode = 85;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['colormap', Type::COLORMAP],
      ['length', Type::CARD16],
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
      ['pixel', Type::CARD32],
      ['exactRed', Type::CARD16],
      ['exactGreen', Type::CARD16],
      ['exactBlue', Type::CARD16],
      ['visualRed', Type::CARD16],
      ['visualGreen', Type::CARD16],
      ['visualBlue', Type::CARD16],
      ['unused', Type::UNUSED, 8]
    ]);

  }

}
