<?php

namespace X11;

class QueryFontRequest extends Request {

  public function __construct($font) {
    $this->sendRequest([
      ['opcode', 47, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['font', $font, Type::FONT]
    ]);
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
      ['unused', Type::BYTE],
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

/*
▶
     1     1                               Reply
     1                                     unused
     2     CARD16                          sequence number
     4     7+2n+3m                         reply length
     12     CHARINFO                       min-bounds
     4                                     unused
     12     CHARINFO                       max-bounds
     4                                     unused
     2     CARD16                          min-char-or-byte2
     2     CARD16                          max-char-or-byte2
     2     CARD16                          default-char
     2     n                               number of FONTPROPs in properties
     1                                     draw-direction
          0     LeftToRight
          1     RightToLeft
     1     CARD8                           min-byte1
     1     CARD8                           max-byte1
     1     BOOL                            all-chars-exist
     2     INT16                           font-ascent
     2     INT16                           font-descent
     4     m                               number of CHARINFOs in char-infos
     8n     LISTofFONTPROP                 properties
     12m     LISTofCHARINFO                char-infos

  FONTPROP
     4     ATOM                            name
     4     <32-bits>                 value

  CHARINFO
     2     INT16                           left-side-bearing
     2     INT16                           right-side-bearing
     2     INT16                           character-width
     2     INT16                           ascent
     2     INT16                           descent
     2     CARD16                          attributes

*/


