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
    return false;
  }

}

/*
  public static function QueryFont() {
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
  }

  public static funciton QueryTextExtents() {
▶
     1     1                               Reply
     1                                     draw-direction
          0     LeftToRight
          1     RightToLeft
     2     CARD16                          sequence number
     4     0                               reply length
     2     INT16                           font-ascent
     2     INT16                           font-descent
     2     INT16                           overall-ascent
     2     INT16                           overall-descent
     4     INT32                           overall-width
     4     INT32                           overall-left
     4     INT32                           overall-right
     4                                     unused
*/
