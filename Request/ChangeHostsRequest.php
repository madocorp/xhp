<?php

namespace X11;

class ChangeHostsRequest extends Request {

  public function __construct($mode, $family, $address) {
    $length = strlen($address);
    $pad = Connection::pad4($length);
    $this->sendRequest([
      ['opcode', 109, Type::BYTE],
      ['mode', $mode, Type::ENUM8, ['Insert', 'Delete']],
      ['requestLength', 2 + (($length + $pad) >> 2), Type::CARD16],
      ['family', $family, Type::ENUM8, ['Internet', 'DECnet', 'Chaos']],
      ['unused', 0, Type::BYTE],
      ['length', $length, Type::CARD16],
      ['address', $address, Type::STRING8],
      ['pad', $pad, Type::PAD4]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}
