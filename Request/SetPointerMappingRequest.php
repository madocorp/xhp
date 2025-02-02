<?php

namespace X11;

class SetPointerMappingRequest extends Request {

  public function __construct($map) {
    $lengthOfMap = count($map);
    $opcode = 116;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['lengthOfMap', Type::CARD8],
      ['requestLength', Type::CARD16],
      ['map', Type::FLIST, [['button', Type::CARD8]]],
      ['pad', Type::UNUSED, Connection::pad4($lengthOfMap)]
    ], $values);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return $this->receiveResponse([
      ['reply', Type::BYTE],
      ['status', Type::ENUM8, ['Success', 'Busy']],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['unused', Type::UNUSED, 24]
    ]);
  }

}
