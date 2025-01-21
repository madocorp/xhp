<?php

namespace X11;

class ChangeKeyboardMappingRequest extends Request {

  public function __construct($firstKeycode, $keysymsPerKeycode, $keysyms) {
    $n = count($keysyms);
    $data = [
      ['opcode', 100, Type::BYTE],
      ['keycodeCount', $n, Type::BYTE],
      ['requestLength', 2 + $n * $keysymsPerKeycode, Type::CARD16],
      ['firstKeycode', $firstKeycode, Type::KEYCODE],
      ['keysymsPerKeycode', $keysymsPerKeycode, Type::CARD8],
      ['unused', 0, Type::CARD16],
    ];
    foreach ($keysyms as $keycode) {
      foreach ($keycode as $keysym) {
        $data[] = ['keysym', $keysym, Type::KEYSYM];
      }
    }
    $this->sendRequest($data);
  }

  protected function processResponse() {
    return false;
  }

}
