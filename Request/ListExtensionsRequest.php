<?php

namespace X11;

class ListExtensionsRequest extends Request {

  public function __construct() {
    $opcode = 99;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
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
    $extensions = [];
    $total = 0;
    $n = $response['n'];
    for ($i = 0; $i < $n; $i++) {
      $length = $this->receiveResponse([
        ['length', Type::CARD8]
      ], false);
      $extension = $this->receiveResponse([
        ['extension', Type::STRING8, $length, false]
      ], false);
      $extensions[] = $extension;
      $total += $length + 1;
    }
    $pad = Connection::pad4($total);
    if ($pad > 0) {
      $this->receiveResponse([
        ['pad', Type::UNUSED, $pad]
      ], false);
    }
    $response['extensions'] = $extension;
    return $response;
  }

}

