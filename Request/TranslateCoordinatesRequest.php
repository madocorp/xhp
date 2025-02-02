<?php

namespace X11;

class TranslateCoordinatesRequest extends Request {

  public function __construct($srcWindow, $dstWindow, $srcX, $srcY) {
    $opcode = 40;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['srcWindow', Type::WINDOW],
      ['dstWindow', Type::WINDOW],
      ['srcX', Type::INT16],
      ['srcY', Type::INT16]
    ], $values);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return $this->receiveResponse([
      ['reply', Type::BYTE],
      ['sameScreen', Type::BOOL],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['child', Type::WINDOW],
      ['dstX', Type::INT16],
      ['dstY', Type::INT16],
      ['unused', Type::UNUSED, 16]
    ]);
  }

}

