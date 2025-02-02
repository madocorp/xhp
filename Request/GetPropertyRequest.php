<?php

namespace X11;

class GetPropertyRequest extends Request {

  public function __construct($delete, $window, $property, $type, $longOffset, $longLength) {
    $opcode = 20;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['delete', Type::BOOL],
      ['requestLength', Type::CARD16],
      ['window', Type::WINDOW],
      ['property', Type::ATOM],
      ['type', Type::ATOM],
      ['longOffset', Type::CARD32],
      ['longLength', Type::CARD32]
    ], $values);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    $response = $this->receiveResponse([
      ['reply', Type::BYTE],
      ['format', Type::CARD8],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['type', Type::ATOM],
      ['bytesAfter', Type::CARD32],
      ['lengthInUnits', Type::CARD32],
      ['unused', Type::UNUSED, 12]
    ]);
    $value = $this->receiveResponse([
      ['value', Type::STRING8, $response['replyLength'] << 2],
    ], false);
    $response['value'] = $value;
    return $response;
  }

}

