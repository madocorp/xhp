<?php

namespace X11;

class QueryTextExtentsRequest extends Request {

  public function __construct($font, $string) {
    $length = strlen($string);
    $pad = Connection::pad4($length);
    $this->sendRequest([
      ['opcode', 48, Type::BYTE],
      ['oddLength', $pad == 2, Type::BOOL],
      ['requestLength', 2, Type::CARD16],
      ['font', $font, Type::FONT],
      ['string', $string, Type::STRING8]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return $this->receiveResponse([
      ['reply', Type::BYTE],
      ['drawDirection', Type::ENUM8, ['LeftToRight', 'RightToLeft']],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['fontAscent', Type::INT16],
      ['fontDescent', Type::INT16],
      ['overallAscent', Type::INT16],
      ['overallDescent', Type::INT16],
      ['overallWidth', Type::INT32],
      ['overallLeft', Type::INT32],
      ['overallRight', Type::INT32],
      ['unused', Type::INT32]
    ]);
  }

}
