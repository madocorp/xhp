<?php

namespace X11;

class ListHostsRequest extends Request {

  public function __construct() {
    $this->sendRequest([
      ['opcode', 110, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 1, Type::CARD16]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    $response = $this->receiveResponse([
      ['reply', Type::BYTE],
      ['mode', Type::ENUM8, ['Disabled', 'Enabled']],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['n', Type::CARD16],
      ['unused', Type::STRING8, 22, false]
    ]);
    $hosts = [];
    $n = $response['n'];
    for ($i = 0; $i < $n; $i++) {
      $host = $this->receiveResponse([
        ['family', Type::ENUM8, ['Internet', 'DECnet', 'Chaos', '-', '-', 'ServerInterpreted', 'InternetV6']],
        ['unused', Type::BYTE],
        ['length', Type::CARD16]
      ], false);
      $address = $this->receiveResponse([
        ['address', Type::STRING8, $host['length']]
      ], false);
      $host['address'] = $address;
      $hosts[] = $host;
    }
    $response['hosts'] = $hosts;
    return $response;
  }

}

