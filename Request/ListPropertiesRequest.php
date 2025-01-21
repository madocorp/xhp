<?php

namespace X11;

class ListPropertiesRequest extends Request {

  public function __construct($wid) {
    $this->sendRequest([
      ['opcode', 21, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['window', $window, Type::WINDOW],
      ['property', $property, Type::ATOM]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}

/*
  public static function ListProperties() {
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     n                               reply length
     2     n                               number of ATOMs in atoms
     22                                    unused
     4n     LISTofATOM                     atoms
*/
