<?php

namespace X11;

class GetKeyboardMappingRequest extends Request {

  public function __construct($firstKeycode, $count) {
    $opcode = 101;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['firstKeycode', Type::KEYCODE],
      ['count', Type::BYTE],
      ['unused', Type::UNUSED, 2]
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
    $m = $response['replyLength'] / $n;
    $keysyms = [];
    for ($i = 0; $i < $n; $i++) {
      $keycode = [];
      for ($j = 0; $j < $m; $j++) {
        $keycode[] = $this->receiveResponse([['keysym', Type::CARD32]], false);
      }
      $keysyms[] = $keycode;
    }
    return $response;
  }

}
