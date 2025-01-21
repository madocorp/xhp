<?php

namespace X11;

class GetPropertyRequest extends Request {

  public function __construct($delete, $window, $property, $type, $longOffset, $longLength) {
    $this->sendRequest([
      ['opcode', 20, Type::BYTE],
      ['delete', $delete, Type::BOOL],
      ['requestLength', 6, Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['property', $property, Type::ATOM],
      ['type', $type, Type::ATOM],
      ['longOffset', $longOffset, Type::CARD32],
      ['longLength', $longLength, Type::CARD32]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}

/*
  public static function GetProperty() {
▶
     1     1                               Reply
     1     CARD8                           format
     2     CARD16                          sequence number
     4     (n+p)/4                         reply length
     4     ATOM                            type
          0     None
     4     CARD32                          bytes-after
     4     CARD32                          length of value in format units
                    (= 0 for format = 0)
                    (= n for format = 8)
                    (= n/2 for format = 16)
                    (= n/4 for format = 32)
     12                                    unused
     n     LISTofBYTE                      value
                    (n is zero for format = 0)
                    (n is a multiple of 2 for format = 16)
                    (n is a multiple of 4 for format = 32)
     p                                     unused, p=pad(n)
*/
