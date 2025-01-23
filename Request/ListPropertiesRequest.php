<?php

namespace X11;

class ListPropertiesRequest extends Request {

  public function __construct($window) {
    $this->sendRequest([
      ['opcode', 21, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['window', $window, Type::WINDOW]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    $response = $this->receiveResponse([
      ['reply', Type::BYTE],
      ['unused', Type::BYTE],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['n', Type::CARD16],
      ['unused', Type::STRING8, 22, false]
    ]);
    $atoms = [];
    $n = $response['n'];
    for ($i = 0; $i < $n; $i++) {
      $atom = $this->receiveResponse([
        ['atom', Type::CARD32]
      ], false);
      $atoms[] = $atom;
    }
    $response['atoms'] = $atoms;
    return $response;
  }

}
