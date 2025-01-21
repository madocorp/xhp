<?php

namespace X11;

class ChangeKeyboardControlRequest extends Request {

  public function __construct($values) {
    $data = [
      ['opcode', 102, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
    ];
    $valueMap = [
      ['keyClickPercent', Type::INT8],
      ['bellPercent', Type::INT8],
      ['bellPitch', Type::INT16],
      ['bellDuration', Type::INT16],
      ['led', Type::CARD8],
      ['ledMode', Type::ENUM8, ['Off', 'On']],
      ['autoRepeatMod', Type::ENUM8, ['Off', 'On', 'Default']],
    ];
    $data = $this->addBitmaskList($data, $valueMap, $values);
    $this->sendRequest($data);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}
