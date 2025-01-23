<?php

namespace X11;

class ListFontsRequest extends Request {

  public function __construct($maxNames, $pattern) {
    $length = strlen($pattern);
    $this->sendRequest([
      ['opcode', 49, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['maxNames', $maxNames, Type::CARD16],
      ['lengthOfPattern', $length, Type::CARD16],
      ['pattern', $pattern, Type::STRING8]
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
    $fonts = [];
    $total = 0;
    $n = $response['n'];
    for ($i = 0; $i < $n; $i++) {
      $length = $this->receiveResponse([
        ['length', Type::CARD8]
      ], false);
      $font = $this->receiveResponse([
        ['font', Type::STRING8, $length, false]
      ], false);
      $fonts[] = $font;
      $total += $length  + 1;
    }
    $pad = Connection::pad4($total);
    if ($pad > 0) {
      $this->receiveResponse([
        ['pad', Type::PAD4, $pad]
      ], false);
    }
    $response['fonts'] = $fonts;
    return $response;
  }

}

