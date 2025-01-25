<?php

namespace X11;

class ChangeKeyboardControlRequest extends Request {

  public function __construct($values) {
    $this->sendRequest([
      ['opcode', 102, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['values', $values, Type::VLIST, [
        ['keyClickPercent', Type::INT8],
        ['bellPercent', Type::INT8],
        ['bellPitch', Type::INT16],
        ['bellDuration', Type::INT16],
        ['led', Type::CARD8],
        ['ledMode', Type::ENUM8, ['Off', 'On']],
        ['key', Type::KEYCODE],
        ['autoRepeatMode', Type::ENUM8, ['Off', 'On', 'Default']]
      ]]
    ]);
  }

}
