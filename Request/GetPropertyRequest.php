<?php

namespace X11;

class GetPropertyRequest extends Request {

  public function __construct($delete, $window, $property, $type, $longOffset, $longLength) {
    $this->sendRequest([
      ['opcode', 20, Type::BYTE],
      ['delete', $delete, Type::BOOL],
      ['requestLength', 6, Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['property', $property, Type::ATOM],
      ['type', $type, Type::ATOM],
      ['longOffset', $longOffset, Type::CARD32],
      ['longLength', $longLength, Type::CARD32]
    ]);
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
      ['unused', Type::STRING8, 12, false]
    ]);
    $value = $this->receiveResponse([
      ['value', Type::STRING8, $response['replyLength'] << 2],
    ], false);
    $response['value'] = $value;
    return $response;
  }

}

