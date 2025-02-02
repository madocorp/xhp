<?php

namespace X11;

class GetModifierMappingRequest extends Request {

  public function __construct() {
    $opcode = 119;
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

