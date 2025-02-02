<?php

namespace X11;

class ChangePropertyRequest extends Request {

  public function __construct($mode, $window, $property, $type, $format, $data) {
    if ($format == 16) {
      $dataLength = strlen($data) >> 1;
    } else if ($format == 32) {
      $dataLength = strlen($data) >> 2;
    } else {
      $format = 8;
      $dataLength = strlen($data);
    }
    $opcode = 18;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['mode', Type::ENUM8, ['Replace', 'Prepend', 'Append']],
      ['requestLength', Type::CARD16],
      ['window', Type::WINDOW],
      ['property', Type::ATOM],
      ['type', Type::ATOM],
      ['format', Type::CARD8],
      ['unused', Type::UNUSED, 3],
      ['dataLength', Type::CARD32],
      ['data', Type::STRING8]
    ], $values);
  }

}
