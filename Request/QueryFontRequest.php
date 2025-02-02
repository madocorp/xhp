<?php

namespace X11;

class QueryFontRequest extends Request {

  public function __construct($font) {
    $opcode = 47;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['font', Type::FONT]
    ], $values);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    $charinfo = [
      ['leftSideBearing', Type::INT16],
      ['rightSideBearing', Type::INT16],
      ['characterWidth', Type::INT16],
      ['ascent', Type::INT16],
      ['descent', Type::INT16],
      ['attributes', Type::CARD16]
    ];
    $fontprop = [
      ['atom', Type::ATOM],
      ['value', Type::CARD32]
    ];
    $response = $this->receiveResponse([
      ['reply', Type::BYTE],
      ['unused', Type::UNUSED, 1],
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
      ['n', Type::CARD16],
      ['drawDirection', Type::ENUM8, ['LeftToRight', 'RightToLeft']],
      ['minByte1', Type::CARD8],
      ['maxByte1', Type::CARD8],
      ['allCharsExist', Type::BOOL],
      ['fontAscent', Type::CARD16],
      ['fontDescent', Type::CARD16],
      ['m', Type::CARD32],
    ], false);
    $n = $response['n'];
    $fontprops = [];
    for ($i = 0; $i < $n; $i++) {
      $prop = $this->receiveResponse($fontprop, false);
      $fontprops[] = $prop;
    }
    $response['fontprops'] = $fontprops;
    $m = $response['m'];
    $charinfos = [];
    for ($i = 0; $i < $m; $i++) {
      $info = $this->receiveResponse($charinfo, false);
      $charinfos[] = $info;
    }
    $response['charinfos'] = $charinfos;
    return $response;
  }

}
