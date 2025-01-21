<?php

namespace X11;

class UngrabKeyRequest extends Request {

  public function __construct($key, $timestamp, $grabWindow, $modifiers) {
    $this->doRequest([
      ['opcode', 34, Type::BYTE],
      ['key', $key, Type::BYTE],
      ['requestLength', 3, Type::CARD16],
      ['timestamp', $timestamp, Type::CARD32],
      ['grabWindow', $grabWindow, Type::WINDOW],
      ['modifiers', $modifiers, Type::CARD16],
      ['unused', 0, Type::CARD16]
    ]);
  }


  protected function processResponse() {
    return false;
  }

}
