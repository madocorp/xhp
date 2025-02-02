<?php

namespace X11;

class GetImageRequest extends Request {

  public function __construct($format, $drawable, $x, $y, $width, $height, $planeMask) {
    $opcode = 73;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['format', Type::ENUM8, [0, 'XYPixmap', 'ZPixmap']],
      ['requestLength', Type::CARD16],
      ['drawable', Type::DRAWABLE],
      ['x', Type::INT16],
      ['y', Type::INT16],
      ['width', Type::CARD16],
      ['height', Type::CARD16],
      ['planeMask', Type::CARD32]
    ], $values);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    $response = $this->receiveResponse([
      ['reply', Type::BYTE],
      ['depth', Type::CARD8],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['visual', Type::VISUALID],
      ['unused', Type::UNUSED, 20]
    ]);
    $data = $this->receiveResponse([
      ['data', Type::STRING8, $response['replyLength'] << 2]
    ], false);
    $response['data'] = $data;
    return $response;
  }

}

