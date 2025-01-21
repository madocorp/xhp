<?php

namespace X11;

class QueryExtensionRequest extends Request {

  public function __construct($name) {
    $length = strlen($name);
    $pad = Connection::pad4($length);
    $this->sendRequest([
      ['opcode', 98, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2 + (($length + $pad) >> 2), Type::CARD16],
      ['length', $length, Type::CARD16],
      ['unused', 0, Type::CARD16],
      ['name', $name, Type::STRING8],
      ['pad', $pad, Type::PAD4]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}

/*
  public static function QueryExtension() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     0                               reply length
     1     BOOL                            present
     1     CARD8                           major-opcode
     1     CARD8                           first-event
     1     CARD8                           first-error
     20                                    unused
*/
