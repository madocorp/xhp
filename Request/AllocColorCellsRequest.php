<?php

namespace X11;

class AllocColorCellsRequest extends Request {

  public function __construct($continguous, $colormap, $colors, $planes) {
    $opcode = 86;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['continguous', Type::BOOL],
      ['requestLength', Type::CARD16],
      ['colormap', Type::COLORMAP],
      ['colors', Type::CARD16],
      ['planes', Type::CARD16]
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
      ['m', Type::CARD16],
      ['unused', Type::UNUSED, 20]
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
