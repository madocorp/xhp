<?php

namespace X11;

class GetFontPathRequest extends Request {

  public function __construct() {
    $opcode = 52;
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
      ['unused', Type::UNUSED, 1],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['n', Type::CARD16],
      ['unused', Type::UNUSED, 22]
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
        ['pad', Type::UNUSED, $pad]
      ], false);
    }
    $response['paths'] = $paths;
    return $response;
  }

}
