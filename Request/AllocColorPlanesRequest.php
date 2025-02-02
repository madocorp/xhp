<?php

namespace X11;

class AllocColorPlanesRequest extends Request {

  public function __construct($continguous, $colormap, $colors, $reds, $greens, $blues) {
    $opcode = 87;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['continguous', Type::BOOL],
      ['requestLength', Type::CARD16],
      ['colormap', Type::COLORMAP],
      ['colors', Type::CARD16],
      ['reds', Type::CARD16],
      ['greens', Type::CARD16],
      ['blues', Type::CARD16]
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
      ['unused', Type::CARD16],
      ['redMask', Type::CARD32],
      ['greenMask', Type::CARD32],
      ['blueMask', Type::CARD32],
      ['unused', Type::UNUSED, 8]
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
