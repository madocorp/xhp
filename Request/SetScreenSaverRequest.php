<?php

namespace X11;

class SetScreenSaverRequest extends Request {

  public function __construct($timeout, $interval, $preferBlanking, $allowExposures) {
    $opcode = 107;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['timeout', Type::INT16],
      ['interval', Type::INT16],
      ['preferBlanking', Type::ENUM8, ['No', 'Yes', 'Default']],
      ['allowExposures', Type::ENUM8, ['No', 'Yes', 'Default']],
      ['unused', Type::UNUSED, 2]
    ], $values);
  }

}
