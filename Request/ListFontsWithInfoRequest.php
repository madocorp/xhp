<?php

namespace X11;

class ListFontsWithInfoRequest extends Request {

  public function __construct($maxNames, $pattern) {
    $this->sendRequest([
      ['opcode', 50, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['maxNames', $maxNames, Type::CARD16],
      ['lengthOfPattern', strlen($pattern), Type::CARD16],
      ['pattern', $pattern, Type::STRING8]
    ]);
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

/*
  public static function ListFontsWithInfo() {
▶ (except for last in series)
     1     1                               Reply
     1     n                               length of name in bytes
     2     CARD16                          sequence number
     4     7+2m+(n+p)/4                    reply length
     12     CHARINFO                       min-bounds
     4                                     unused
     12     CHARINFO                       max-bounds
     4                                     unused
     2     CARD16                          min-char-or-byte2
     2     CARD16                          max-char-or-byte2
     2     CARD16                          default-char
     2     m                               number of FONTPROPs in properties
     1                                     draw-direction
          0     LeftToRight
          1     RightToLeft
     1     CARD8                           min-byte1
     1     CARD8                           max-byte1
     1     BOOL                            all-chars-exist
     2     INT16                           font-ascent
     2     INT16                           font-descent
     4     CARD32                          replies-hint
     8m     LISTofFONTPROP                 properties
     n     STRING8                         name
     p                                     unused, p=pad(n)

  FONTPROP
     encodings are the same as for QueryFont

  CHARINFO
     encodings are the same as for QueryFont

▶ (last in series)
     1     1                               Reply
     1     0                               last-reply indicator
     2     CARD16                          sequence number
     4     7                               reply length
     52                                    unused
*/
