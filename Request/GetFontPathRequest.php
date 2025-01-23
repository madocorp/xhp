<?php

namespace X11;

class GetFontPathRequest extends Request {

  public function __construct() {
    $this->sendRequest([
      ['opcode', 52, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 1, Type::CARD16]
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
    $paths = [];
    $total = 0;
    $n = $response['n'];
    for ($i = 0; $i < $n; $i++) {
      $length = $this->receiveResponse([
        ['length', Type::CARD8]
      ], false);
      $path = $this->receiveResponse([
        ['path', Type::STRING8, $length, false]
      ], false);
      $paths[] = $path;
      $total += $length  + 1;
    }
    $pad = Connection::pad4($total);
    if ($pad > 0) {
      $this->receiveResponse([
        ['pad', Type::PAD4, $pad]
      ], false);
    }
    $response['paths'] = $paths;
    return $response;
  }

}
