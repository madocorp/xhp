<?php

namespace X11;

class ListExtensionsRequest extends Request {

  public function __construct() {
    $this->sendRequest([
      ['opcode', 99, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 1, Type::CARD16],
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
        ['pad', Type::PAD4, $pad]
      ], false);
    }
    $response['extensions'] = $extension;
    return $response;
  }

}

/*
  public static function ListExtensions() {
▶
     1     1                               Reply
     1     CARD8                           number of STRs in names
     2     CARD16                          sequence number
     4     (n+p)/4                         reply length
     24                                    unused
     n     LISTofSTR                       names
     p                                     unused, p=pad(n)
*/
