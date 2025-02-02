<?php

namespace X11;

class QueryTextExtentsRequest extends Request {

  public function __construct($font, $string) {
    $length = strlen($string);
    $oddLength = (Connection::pad4($length) == 2);
    $opcode = 48;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['oddLength', Type::BOOL],
      ['requestLength', Type::CARD16],
      ['font', Type::FONT],
      ['string', Type::STRING8]
    ], $values);
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
      ['unused', Type::UNUSED, 4]
    ]);
  }

}
