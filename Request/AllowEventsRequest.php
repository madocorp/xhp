<?php

namespace X11;

class AllowEventsRequest extends Request {

  public function __construct($mode, $timestamp) {
    $opcode = 35;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['mode', Type::ENUM8, ['AsyncPointer', 'SyncPointer', 'ReplayPointer', 'AsyncKeyboard', 'SyncKeyboard', 'ReplayKeyboard', 'AsyncBoth', 'SyncBoth']],
      ['requestLength', Type::CARD16],
      ['timestamp', Type::CARD32]
    ], $values);
  }

}
