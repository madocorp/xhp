<?php

namespace X11;

class QueryTreeRequest extends Request {

  public function __construct($window) {
    $opcode = 15;
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
      ['root', Type::WINDOW],
      ['parent', Type::WINDOW],
      ['n', Type::CARD16],
      ['unused', Type::UNUSED, 14]
    ]);
    $n = $response['n'];
    $children = [];
    for ($i = 0; $i < $n; $i++) {
      $child = $this->receiveResponse([
        ['child', Type::WINDOW]
      ], false);
      $children[] = $child;
    }
    $response['children'] = $children;
    return $response;
  }

}
