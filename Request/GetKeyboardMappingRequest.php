<?php

namespace X11;

class GetKeyboardMappingRequest extends Request {

  public function __construct($firstKeycode, $count) {
    $this->sendRequest([
      ['opcode', 101, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['firstKeycode', $firstKeycode, Type::KEYCODE],
      ['count', $count, Type::BYTE],
      ['unused', 0, Type::CARD16]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    $response = $this->receiveResponse([
      ['reply', Type::BYTE],
      ['n', Type::CARD8],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['autoRepeats', Type::STRING8, 24, false]
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
