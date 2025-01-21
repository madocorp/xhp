<?php

namespace X11;

class SetScreenSaverRequest extends Request {

  public function __construct($timeout, $interval, $preferBlanking, $allowExposures) {
    $this->doRequest([
      ['opcode', 107, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['timeout', $timeout, Type::INT16],
      ['interval', $interval, Type::INT16],
      ['preferBlanking', $prefereBlanking, Type::ENUM8, ['No', 'Yes', 'Default']],
      ['allowExposures', $allowExposures, Type::ENUM8, ['No', 'Yes', 'Default']],
      ['unused', 0, Type::CARD16]
    ]);
  }



  protected function processResponse() {
    return false;
  }

}
