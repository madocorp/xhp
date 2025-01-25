<?php

namespace X11;

class AllocColorCellsRequest extends Request {

  public function __construct($continguous, $colormap, $colors, $planes) {
    $this->sendRequest([
      ['opcode', 86, Type::BYTE],
      ['continguous', $continguous, Type::BOOL],
      ['requestLength', 3, Type::CARD16],
      ['colormap', $colormap, Type::COLORMAP],
      ['colors', $colors, Type::CARD16],
      ['planes', $planes, Type::CARD16]
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
      ['m', Type::CARD16],
      ['unused', Type::STRING8, 20, false]
    ]);
    $pixels = [];
    for ($i = 0; $i < $response['n']; $i++) {
      $pixels[] = $this->receiveResponse([
        ['data', Type::CARD32]
      ], false);
    }
    $response['pixels'] = $pixels;
    $masks = [];
    for ($i = 0; $i < $response['m']; $i++) {
      $masks[] = $this->receiveResponse([
        ['data', Type::CARD32]
      ], false);
    }
    $response['masks'] = $masks;
    return $response;
  }

}
