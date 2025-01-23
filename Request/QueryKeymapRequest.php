<?php

namespace X11;

class QueryKeymapRequest extends Request {

  public function __construct() {
    $this->sendRequest([
      ['opcode', 44, Type::BYTE],
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
      ['keys', Type::STRING8, 32, false]
    ]);
    $response['keys'] = array_values(unpack('C32', $response['keys']));
    return $response;
  }

}

/*
  public static function QueryKeymap() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     2                               reply length
     32     LISTofCARD8                    keys
*/
