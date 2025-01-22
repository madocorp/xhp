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
    $list = [];
    $n = $response['n'];
    $total = 0;
    for ($i = 0; $i < $n; $i++) {
      $length = $this->receiveResponse([
        ['length', Type::CARD8]
      ], false);
      $font = $this->receiveResponse([
        ['font', Type::STRING8, $length, false]
      ], false);
      $list[] = $font;
      $total += $length;
    }
    $response['list'] = $list;
    return $response;
  }

}

/*
  public static function ListFonts() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     (n+p)/4                         reply length
     2     CARD16                          number of STRs in names
     22                                    unused
     n     LISTofSTR                       names
     p                                     unused, p=pad(n)
*/
