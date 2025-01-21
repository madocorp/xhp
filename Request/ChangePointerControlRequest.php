<?php

namespace X11;

class ChangePointerControlRequest extends Request {

  public function __construct($accelearatorNumerator, $acceleratorDenominator, $thresold, $doAcceleration, $doThresold) {
    $this->doRequest([
      ['opcode', 105, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['accelerationNumrator', $accelerationNumerator, Type::INT16],
      ['accelerationDenominator', $accelerationDenominator, Type::INT16],
      ['thresold', $thresold, Type::INT16],
      ['doAcceleration', $doAcceleration, Type::BOL],
      ['doThresold', $doThresold, Type::BOOL]
    ]);
  }


  protected function processResponse() {
    return false;
  }

}
