<?php

namespace X11;

class SetModifierMappingRequest extends Request {

  public function __construct($keycodesPerModifier, $keycodes) {
    $flatKeycodes = [];
    for ($j = 0; $j < 8; $j++) {
      for ($i = 0; $i < $keycodesPerModifier; $i++) {
        if (isset($keycodes[$j][$i])) {
          $flatKeycodes[] = $keycodes[$j][$i];
        } else {
          $flatKeycodes[] = 0;
        }
      }
    }
    $keycodes = $flatKeycodes;
    unset($flatKeycodes);
    $opcode = 118;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['keycodesPerModifier', Type::CARD8],
      ['requestLength', Type::CARD16],
      ['keycodes', Type::FLIST, [['keycode', Type::KEYCODE]]]
    ], $values);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return $this->receiveResponse([
      ['reply', Type::BYTE],
      ['status', Type::ENUM8, ['Success', 'Busy', 'Failed']],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['unused', Type::UNUSED, 24]
    ]);
  }

}
