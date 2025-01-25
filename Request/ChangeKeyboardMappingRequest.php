<?php

namespace X11;

class ChangeKeyboardMappingRequest extends Request {

  public function __construct($firstKeycode, $keysymsPerKeycode, $keysyms) {

    $n = count($keysyms);
    $flatKeysyms = [];
    foreach ($keysyms as $keycode) {
      for ($i = 0; $i < $keysymsPerKeycode; $i++) {
        if (isset($keycode[$i])) {
          $flatKeysyms[] = ['keysym' => $keycode[$i]];
        } else {
          $flatKeysyms[] = ['keysym' => 0];
        }
      }
    }
    $this->sendRequest([
      ['opcode', 100, Type::BYTE],
      ['keycodeCount', $n, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['firstKeycode', $firstKeycode, Type::KEYCODE],
      ['keysymsPerKeycode', $keysymsPerKeycode, Type::CARD8],
      ['unused', 0, Type::CARD16],
      ['keysyms', $flatKeysyms, Type::FLIST, [
        ['keysym', Type::KEYSYM]
      ]]
    ]);
  }

}
