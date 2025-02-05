<?php

namespace X11;

class GetPointerMappingRequest extends Request {

  public function __construct() {
    $opcode = 117;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16]
    ], $values);
    Connection::setResponse($this->processResponse());
  }


  protected function processResponse() {
    $response = $this->receiveResponse([
      ['reply', Type::BYTE],
      ['n', Type::CARD8],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['unused', Type::UNUSED, 24]
    ]);
    $n = $response['n'];
    $p = Connection::pad4($n);
    $mapping = $this->receiveResponse([['map', Type::STRING8, $n, false]], false);
    $response['mapping'] = unpack("C{$n}", $mapping);
    $this->receiveResponse([['unused', Type::UNUSED, $p]], false);
    return $response;
  }

}

