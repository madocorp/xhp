<?php

namespace X11;

class SetPointerMappingRequest extends Request {

  public function __construct($map) {
    $length = count($map);
    $pad = Connection::pad4($length);
    $data = [
      ['opcode', 116, Type::BYTE],
      ['lengthOfMap', $length, Type::CARD8],
      ['requestLength', 1 + (($length + $pad) >> 2), Type::CARD16],
    ];
    foreach ($map as $button) {
      $data[] = ['button', $button, Type::CARD8];
    }
    $data[] = ['pad', $pad, Type::PAD4];
    $this->doRequest($data);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}
