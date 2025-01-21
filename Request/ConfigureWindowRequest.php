<?php

namespace X11;

class ConfigureWindowRequest extends Request {

  public function __construct($window, $values) {
    $this->sendRequest([
      ['opcode', 12, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['values', $values, Type::VLIST, [
        ['x', Type::INT16],
        ['y', Type::INT16],
        ['width', Type::CARD16],
        ['height', Type::CARD16],
        ['borderWidth', Type::CARD16],
        ['sibling', Type::WINDOW],
        ['stackMode', Type::ENUM8, ['Above', 'Below', 'TopIf', 'BottomIf', 'Opposite']]
      ]]
    ]);
  }

}
