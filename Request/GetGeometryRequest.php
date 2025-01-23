<?php

namespace X11;

class GetGeometryRequest extends Request {

  public function __construct($drawable) {
    $this->sendRequest([
      ['opcode', 14, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['drawable', $drawable, Type::DRAWABLE]
    ]);
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
      ['unused', Type::STRING8, 10, false]
    ]);
  }

}
