<?php

namespace X11;

class SetModifierMappingRequest extends Request {

  public function __construct($keycodesPerModifier, $map) {
    $length = count($map);
    $pad = Connection::pad4($length);
    $data = [
      ['opcode', 118, Type::BYTE],
      ['keycodesPerModifier', $keycodesPerModifier, Type::CARD8],
      ['requestLength', 1 + 2 * $length, Type::CARD16],
    ];
    foreach ($map as $keycode) {
      $data[] = ['keycode', $keycode, Type::KEYCODE];
    }
    $data[] = ['pad', $pad, Type::PAD4];
    $this->sendRequest($data);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}

/*
  public static function SetModifierMapping() {
▶
     1     1                               Reply
     1                                     status
          0     Success
          1     Busy
          2     Failed
     2     CARD16                          sequence number
     4     0                               reply length
     24                                    unused
*/
