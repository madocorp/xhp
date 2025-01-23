<?php

namespace X11;

class QueryColorsRequest extends Request {

  public function __construct($colormap, $pixels) {
    $this->sendRequest([
      ['opcode', 91, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['cmap', $colormap, Type::COLORMAP],
      ['pixels', $pixels, Type::FLIST, [
        ['pixel', Type::CARD32]
      ]]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    $response = $this->receiveResponse([
      ['reply', Type::BYTE],
      ['unused', Type::BYTE],
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
        ['unused', Type::CARD16]
      ], false);
      $colors[] = $color;
    }
    $response['colors'] = $colors;
    return $response;
  }

}

