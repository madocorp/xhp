<?php

namespace X11;

class SetFontPathRequest extends Request {

  public function __construct($paths) {
    $opcode = 51;
    $numberOfStrings = count($paths);
    $values = get_defined_vars();
    $totalLength = 0;
    foreach ($paths as $path) {
      $totalLength += strlen($path) + 1;
    }
    $pad = Connection::pad4($totalLength);
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['numberOfStrings', Type::CARD16],
      ['unused', Type::UNUSED, 2],
      ['paths', Type::FLIST, [
        ['path', Type::STR]
      ]],
      ['unused', Type::UNUSED, $pad]
    ], $values);
  }

}
