<?php

namespace X11;

class ChangeHostsRequest extends Request {

  public function __construct($mode, $family, $address) {
    $length = strlen($address);
    $opcode = 109;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['mode', Type::ENUM8, ['Insert', 'Delete']],
      ['requestLength', Type::CARD16],
      ['family', Type::ENUM8, ['Internet', 'DECnet', 'Chaos']],
      ['unused', Type::UNUSED, 1],
      ['length', Type::CARD16],
      ['address', Type::STRING8]
    ], $values);
  }

}
