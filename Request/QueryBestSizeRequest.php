<?php

namespace X11;

class QueryBestSizeRequest extends Request {

  public function __construct($class, $drawable, $width, $height) {
    $opcode = 97;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['class', Type::ENUM8, ['Cursor', 'Tile', 'Stipple']],
      ['requestLength', Type::CARD16],
      ['drawable', Type::CURSOR],
      ['width', Type::CARD16],
      ['height', Type::CARD16]
    ], $values);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return $this->receiveResponse([
      ['reply', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['width', Type::CARD16],
      ['height', Type::CARD16],
      ['unused', Type::UNUSED, 20]
    ]);
  }

}

