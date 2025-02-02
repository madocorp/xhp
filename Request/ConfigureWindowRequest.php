<?php

namespace X11;

class ConfigureWindowRequest extends Request {

  public function __construct($window, $values) {
    $opcode = 12;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['window', Type::WINDOW],
      ['values', Type::VLIST, [
        ['x', Type::INT16],
        ['y', Type::INT16],
        ['width', Type::CARD16],
        ['height', Type::CARD16],
        ['borderWidth', Type::CARD16],
        ['sibling', Type::WINDOW],
        ['stackMode', Type::ENUM8, ['Above', 'Below', 'TopIf', 'BottomIf', 'Opposite']]
      ]]
    ], $values);
  }

}
