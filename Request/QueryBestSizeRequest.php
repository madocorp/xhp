<?php

namespace X11;

class QueryBestSizeRequest extends Request {

  public function __construct($class, $drawable, $width, $height) {
    $this->sendRequest([
      ['opcode', 97, Type::BYTE],
      ['class', $class, Type::ENUM8, ['Cursor', 'Tile', 'Stipple']],
      ['requestLength', 3, Type::CARD16],
      ['drawable', $drawable, Type::CURSOR],
      ['width', $width, Type::CARD16],
      ['height', $height, Type::CARD16]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return $this->receiveResponse([
      ['reply', Type::BYTE],
      ['unused', Type::BYTE],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['width', Type::CARD16],
      ['height', Type::CARD16],
      ['unused', Type::STRING8, 20, false]
    ]);
  }

}

