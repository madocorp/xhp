<?php

namespace X11;

class GetPointerMappingRequest extends Request {

  public function __construct() {
    $this->sendRequest([
      ['opcode', 117, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 1, Type::CARD16]
    ]);
    Connection::setResponse($this->processResponse());
  }


  protected function processResponse() {
    $response = $this->receiveResponse([
      ['reply', Type::BYTE],
      ['n', Type::CARD8],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['unused', Type::STRING8, 24, false]
    ]);
    $n = $response['n'];
    $mapping = $this->receiveResponse([['map', Type::STRING8, $n, false]], false);
    $response['mapping'] = unpack("C{$n}", $mapping);
    return $response;
  }

}

