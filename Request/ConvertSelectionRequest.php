<?php

namespace X11;

class ConvertSelectionRequest extends Request {

  public function __construct($requestor, $selection, $target, $property, $timestamp) {
    $this->sendRequest([
      ['opcode', 24, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 6, Type::CARD16],
      ['requestor', $requestor, Type::WINDOW],
      ['selection', $selection, Type::ATOM],
      ['target', $target, Type::ATOM],
      ['property', $property, Type::ATOM],
      ['timestamp', $timestamp, Type::TIMESTAMP]
    ]);
  }

  protected function processResponse() {
    return false;
  }

}
