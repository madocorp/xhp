<?php

namespace X11;

class ListPropertiesRequest extends Request {

  public function __construct($window) {
    $opcode = 21;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['window', Type::WINDOW]
    ], $values);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    $response = $this->receiveResponse([
      ['reply', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['n', Type::CARD16],
      ['unused', Type::UNUSED, 22]
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
