<?php

namespace X11;

class SetDashesRequest extends Request {

  public function __construct($gc, $dashOffset, $dashes) {
    $n = count($dashes);
    $opcode = 58;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['gc', Type::GCONTEXT],
      ['dashOffset', Type::CARD16],
      ['n', Type::CARD16],
      ['dashes', Type::FLIST, [['dash', Type::CARD8]]],
      ['pad', Type::UNUSED, Connection::pad4($n)]
    ], $values);
  }

}
