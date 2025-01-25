<?php

namespace X11;

class AllocColorPlanesRequest extends Request {

  public function __construct($continguous, $colormap, $colors, $reds, $greens, $blues) {
    $this->sendRequest([
      ['opcode', 87, Type::BYTE],
      ['continguous', $continguous, Type::BOOL],
      ['requestLength', 4, Type::CARD16],
      ['colormap', $colormap, Type::COLORMAP],
      ['colors', $colors, Type::CARD16],
      ['reds', $reds, Type::CARD16],
      ['greens', $greens, Type::CARD16],
      ['bluess', $blues, Type::CARD16]
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
      ['unused', Type::CARD16],
      ['redMask', Type::CARD32],
      ['greenMask', Type::CARD32],
      ['blueMask', Type::CARD32],
      ['unused', Type::STRING8, 8, false]
    ]);
    $pixels = [];
    for ($i = 0; $i < $response['n']; $i++) {
      $pixels[] = $this->receiveResponse([
        ['data', Type::CARD32]
      ], false);
    }
    $response['pixels'] = $pixels;
    return $response;
  }

}
