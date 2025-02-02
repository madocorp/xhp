<?php

namespace X11;

class RotatePropertiesRequest extends Request {

  public function __construct($window, $delta, $properties) {
    $numberOfProperties = count($properties);
    $opcode = 114;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['window', Type::WINDOW],
      ['numberOfProperties', Type::CARD16],
      ['delta', Type::INT16],
      ['properties', Type::FLIST,
        [['property', Type::ATOM]]
      ]
    ], $values);
  }

}
