<?php

namespace X11;

class QueryTreeRequest extends Request {

  public function __construct($window) {
    $this->sendRequest([
      ['opcode', 15, Type::BYTE],
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
      ['root', Type::WINDOW],
      ['parent', Type::WINDOW],
      ['n', Type::CARD16],
      ['unused', Type::STRING8, 14, false]
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
