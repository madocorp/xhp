<?php

namespace X11;

class GetMotionEventsRequest extends Request {

  public function __construct($window, $start, $stop) {
    $this->sendRequest([
      ['opcode', 39, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4, Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['start', $start, Type::TIMESTAMP],
      ['stop', $stop, Type::TIMESTAMP]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    $response = $this->receiveResponse([
      ['reply', Type::BYTE],
      ['unused', Type::BYTE],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['n', Type::CARD32],
      ['unused', Type::STRING8, 20, false]
    ]);
    $n = $response['n'];
    $events = [];
    for ($i = 0; $i < $n; $i++) {
      $events[] = $this->receiveResponse([
        ['time', Type::TIMESTAMP],
        ['x', Type::INT16],
        ['y', Type::INT16]
      ], false);
    }
    $response['events'] = $events;
    return $response;
  }

}
