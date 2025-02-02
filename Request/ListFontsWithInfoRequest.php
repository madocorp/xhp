<?php

namespace X11;

class ListFontsWithInfoRequest extends Request {

  public function __construct($maxNames, $pattern) {
    $lengthOfPattern = strlen($pattern);
    $opcode = 50;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['maxNames', Type::CARD16],
      ['lengthOfPattern', Type::CARD16],
      ['pattern', Type::STRING8]
    ], $values);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    $response = [];
    $fontprop = [
      ['atom', Type::ATOM],
      ['value', Type::CARD32]
    ];
    while (true) {
      $info = $this->receiveResponse([
        ['reply', Type::BYTE],
        ['n', Type::CARD8],
        ['sequenceNumber', Type::CARD16],
        ['replyLength', Type::CARD32],
        ['minBoundsLeftSideBearing', Type::INT16],
        ['minBoundsRightSideBearing', Type::INT16],
        ['minBoundsCharacterWidth', Type::INT16],
        ['minBoundsAscent', Type::INT16],
        ['minBoundsDescent', Type::INT16],
        ['minBoundsAttributes', Type::CARD16],
        ['unused', Type::CARD32],
        ['maxBoundsLeftSideBearing', Type::INT16],
        ['maxBoundsRightSideBearing', Type::INT16],
        ['maxBoundsCharacterWidth', Type::INT16],
        ['maxBoundsAscent', Type::INT16],
        ['mxaBoundsDescent', Type::INT16],
        ['maxBoundsAttributes', Type::CARD16],
        ['unused', Type::CARD32],
        ['minCharOrByte2', Type::CARD16],
        ['maxCharOrByte2', Type::CARD16],
        ['defaultChar', Type::CARD16],
        ['m', Type::CARD16],
        ['drawDirection', Type::ENUM8, ['LeftToRight', 'RightToLeft']],
        ['minByte1', Type::CARD8],
        ['maxByte1', Type::CARD8],
        ['allCharsExist', Type::BOOL],
        ['fontAscent', Type::CARD16],
        ['fontDescent', Type::CARD16],
        ['repliesHint', Type::CARD32]
      ]);
      if ($info['n'] == 0) {
        break;
      }
      $m = $info['m'];
      $fontprops = [];
      for ($i = 0; $i < $m; $i++) {
        $prop = $this->receiveResponse($fontprop, false);
        $fontprops[] = $prop;
      }
      $info['fontprops'] = $fontprops;
      $info['name'] = $this->receiveResponse([['name', Type::STRING8, $info['n']]], false);
      $respnose[] = $info;
    }
    return $response;
  }

}

