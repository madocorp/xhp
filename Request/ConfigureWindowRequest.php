<?php

namespace X11;

class ConfigureWindowRequest extends Request {

  public function __construct($window, $values) {
    $data = [
      ['opcode', 12, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['window', $window, Type::WINDOW]
    ];
    $valueMap = [
      ['x', Type::INT16],
      ['y', Type::INT16],
      ['width', Type::CARD16],
      ['height', Type::CARD16],
      ['borderWidth', Type::CARD16],
      ['sibling', Type::WINDOW],
      ['stackMode', Type::ENUM8, ['Above', 'Below', 'TopIf', 'BottomIf', 'Opposite']]
    ];
    $data = $this->addBitmaskList($data, $valueMap, $values);
    $this->doRequest($data);
  }

  protected function processResponse() {
    return false;
  }

}
