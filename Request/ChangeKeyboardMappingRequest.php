<?php

namespace X11;

class ChangeKeyboardMappingRequest extends Request {

  public function __construct($firstKeycode, $keysymsPerKeycode, $keysyms) {
    $keycodeCount = count($keysyms);
    $flatKeysyms = [];
    foreach ($keysyms as $keycode) {
      for ($i = 0; $i < $keysymsPerKeycode; $i++) {
        if (isset($keycode[$i])) {
          $flatKeysyms[] = $keycode[$i];
        } else {
          $flatKeysyms[] = 'keysym';
        }
      }
    }
    $keysyms = $flatKeysyms;
    unset($flatKeysyms);
    $opcode = 100;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['keycodeCount', Type::BYTE],
      ['requestLength', Type::CARD16],
      ['firstKeycode', Type::KEYCODE],
      ['keysymsPerKeycode', Type::CARD8],
      ['unused', Type::UNUSED, 2],
      ['keysyms', Type::FLIST, [
        ['keysym', Type::KEYSYM]
      ]]
    ], $values);
  }

}
