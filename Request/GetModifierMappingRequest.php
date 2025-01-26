<?php

namespace X11;

class GetModifierMappingRequest extends Request {

  public function __construct() {
    $this->sendRequest([
      ['opcode', 119, Type::BYTE],
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
    $map = [];
    for ($i = 0; $i < $response['n']; $i++) {
      $map[] =  ["modifier{$i}", Type::KEYCODE];
    }
    $modifiers = [];
    for ($i = 0; $i < 8; $i++) {
      $modifiers[] = array_values($this->receiveResponse($map, false));
    }
    $response['mapping'] = $modifiers;
    return $response;
  }

}

