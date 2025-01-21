<?php

namespace X11;

class ListFontsWithInfoRequest extends Request {

  public function __construct($maxNames, $pattern) {
    $length = strlen($pattern);
    $this->sendRequest([
      ['opcode', 50, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['maxNames', $maxNames, Type::CARD16],
      ['lengthOfPattern', $length, Type::PAD4],
      ['pattern', $pattern, Type::STRING8],
      ['pad', Connection::pad4($length), Type::PAD4]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return false;
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
