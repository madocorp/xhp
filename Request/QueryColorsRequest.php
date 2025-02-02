<?php

namespace X11;

class QueryColorsRequest extends Request {

  public function __construct($cmap, $pixels) {
    $opcode = 91;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['cmap', Type::COLORMAP],
      ['pixels', Type::FLIST, [
        ['pixel', Type::CARD32]
      ]]
    ], $values);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    $response = $this->receiveResponse([
      ['reply', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['n', Type::CARD16]
    ]);
    $n = $response['n'];
    $colors = [];
    for ($i = 0; $i < $n; $i++) {
      $color = $this->receiveResponse([
        ['red', Type::CARD16],
        ['green', Type::CARD16],
        ['blue', Type::CARD16],
        ['unused', Type::UNUSED, 2]
      ], false);
      $colors[] = $color;
    }
    $response['colors'] = $colors;
    return $response;
  }

}

