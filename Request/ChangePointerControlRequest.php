<?php

namespace X11;

class ChangePointerControlRequest extends Request {

  public function __construct($accelerationNumerator, $accelerationDenominator, $thresold, $doAcceleration, $doThresold) {
    $opcode = 105;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['accelerationNumerator', Type::INT16],
      ['accelerationDenominator', Type::INT16],
      ['thresold', Type::INT16],
      ['doAcceleration', Type::BOOL],
      ['doThresold', Type::BOOL]
    ], $values);
  }

}
