<?php

namespace X11;

class RotatePropertiesRequest extends Request {

  public function __construct($window, $delta, $properties) {
    $n = count($properties);
    $data = [
      ['opcode', 114, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 4 + $n, Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['numberOfProperties', $n, Type::CARD16],
      ['delta', $delta, Type::INT16],
    ];
    foreach ($properties as $property) {
      $data[] = ['property', $property, Type::ATOM];
    }
    $this->doRequest($data);
  }

  protected function processResponse() {
    return false;
  }

}
