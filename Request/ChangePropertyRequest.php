<?php

namespace X11;

class ChangePropertyRequest extends Request {

  public function __construct($mode, $window, $property, $type, $format, $data) {
    $length = strlen($data);
    if ($format == 16) {
      $lengthInFormatUnit = $length >> 1;
    } else if ($format == 32) {
      $lengthInFormatUnit = $length >> 2;
    } else {
      $format = 8;
      $lengthInFormatUnit = $length;
    }
    $this->sendRequest([
      ['opcode', 18, Type::BYTE],
      ['mode', $mode, Type::ENUM8, ['Replace', 'Prepend', 'Append']],
      ['requestLength', 6, Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['property', $property, Type::ATOM],
      ['type', $type, Type::ATOM],
      ['format', $format, Type::CARD8],
      ['unused', 0, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['dataLength', $lengthInFormatUnit, Type::CARD32],
      ['data', $data, Type::STRING8]
    ]);
  }

}
