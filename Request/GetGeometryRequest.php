<?php

namespace X11;

class GetGeometryRequest extends Request {

  public function __construct($drawable) {
    $opcode = 14;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['drawable', Type::DRAWABLE]
    ], $values);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return $this->receiveResponse([
      ['reply', Type::BYTE],
      ['depth', Type::CARD8],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['window', Type::WINDOW],
      ['x', Type::INT16],
      ['y', Type::INT16],
      ['width', Type::CARD16],
      ['height', Type::CARD16],
      ['borderWidth', Type::CARD16],
      ['unused', Type::UNUSED, 10]
    ]);
  }

}
