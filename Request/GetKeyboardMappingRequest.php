<?php

namespace X11;

class GetKeyboardMappingRequest extends Request {

  public function __construct($firstKeycode, $count) {
    $this->sendRequest([
      ['opcode', 101, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['firstKeycode', $firstKeycode, Type::KEYCODE],
      ['count', $count, Type::BYTE],
      ['unused', 0, Type::CARD16]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}

/*
  public static function GetKeyboardMapping() {
▶
     1     1                               Reply
     1     n                               keysyms-per-keycode
     2     CARD16                          sequence number
     4     nm                              reply length (m = count field
                                           from the request)
     24                                    unused
     4nm     LISTofKEYSYM                  keysyms
*/
