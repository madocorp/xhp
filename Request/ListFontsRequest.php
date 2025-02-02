<?php

namespace X11;

class ListFontsRequest extends Request {

  public function __construct($maxNames, $pattern) {
    $lengthOfPattern = strlen($pattern);
    $opcode = 49;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['maxNames', Type::CARD16],
      ['lengthOfPattern', Type::CARD16],
      ['pattern', Type::STRING8]
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
        ['pad', Type::UNUSED, $pad]
      ], false);
    }
    $response['fonts'] = $fonts;
    return $response;
  }

}

