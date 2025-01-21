<?php

namespace X11;

class AllowEventsRequest extends Request {

  public function __construct($mode, $timestamp) {
    $this->sendRequest([
      ['opcode', 35, Type::BYTE],
      ['mode', $mode, Type::ENUM8, ['AsyncPointer', 'SyncPointer', 'ReplayPointer', 'AsyncKeyboard', 'SyncKeyboard', 'ReplayKeyboard', 'AsyncBoth', 'SyncBoth']],
      ['requestLength', 2, Type::CARD16],
      ['timestamp', $timestamp, Type::CARD32]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
  }

}
