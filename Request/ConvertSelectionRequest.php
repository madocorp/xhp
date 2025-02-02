<?php

namespace X11;

class ConvertSelectionRequest extends Request {

  public function __construct($requestor, $selection, $target, $property, $timestamp) {
    $opcode = 24;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['requestor', Type::WINDOW],
      ['selection', Type::ATOM],
      ['target', Type::ATOM],
      ['property', Type::ATOM],
      ['timestamp', Type::TIMESTAMP]
    ], $values);
  }

}
