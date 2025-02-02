<?php

namespace X11;

class GetMotionEventsRequest extends Request {

  public function __construct($window, $start, $stop) {
    $opcode = 39;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['window', Type::WINDOW],
      ['start', Type::TIMESTAMP],
      ['stop', Type::TIMESTAMP]
    ], $values);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    $response = $this->receiveResponse([
      ['reply', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['n', Type::CARD32],
      ['unused', Type::UNUSED, 20]
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
