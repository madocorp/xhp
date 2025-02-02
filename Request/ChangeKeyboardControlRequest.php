<?php

namespace X11;

class ChangeKeyboardControlRequest extends Request {

  public function __construct($values) {
    $opcode = 102;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['values', Type::VLIST, [
        ['keyClickPercent', Type::INT8],
        ['bellPercent', Type::INT8],
        ['bellPitch', Type::INT16],
        ['bellDuration', Type::INT16],
        ['led', Type::CARD8],
        ['ledMode', Type::ENUM8, ['Off', 'On']],
        ['key', Type::KEYCODE],
        ['autoRepeatMode', Type::ENUM8, ['Off', 'On', 'Default']]
      ]]
    ], $values);
  }

}
