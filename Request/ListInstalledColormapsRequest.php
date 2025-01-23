<?php

namespace X11;

class ListInstalledColormapsRequest extends Request {

  public function __construct($window) {
    $this->sendRequest([
      ['opcode', 83, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['window', $window, Type::WINDOW]
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
    $colormaps = [];
    $n = $response['n'];
    for ($i = 0; $i < $n; $i++) {
      $colormap = $this->receiveResponse([
        ['colormap', Type::CARD32]
      ], false);
      $colormaps[] = $colormap;
    }
    $response['colormaps'] = $colormaps;
    return $response;
  }

}

