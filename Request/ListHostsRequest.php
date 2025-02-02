<?php

namespace X11;

class ListHostsRequest extends Request {

  public function __construct() {
    $opcode = 110;
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
      ['mode', Type::ENUM8, ['Disabled', 'Enabled']],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['n', Type::CARD16],
      ['unused', Type::UNUSED, 22]
    ]);
    $hosts = [];
    $n = $response['n'];
    for ($i = 0; $i < $n; $i++) {
      $host = $this->receiveResponse([
        ['family', Type::ENUM8, ['Internet', 'DECnet', 'Chaos', '-', '-', 'ServerInterpreted', 'InternetV6']],
        ['unused', Type::UNUSED, 1],
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

