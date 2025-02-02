<?php

namespace X11;

class GetWindowAttributesRequest extends Request {

  public function __construct($window) {
    $opcode = 3;
    $values = get_defined_vars();
    $this->sendRequest([
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['window', Type::WINDOW]
    ], $values);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    return $this->receiveResponse([
      ['reply', Type::BYTE],
      ['backingStore', Type::ENUM8, ['NotUseful', 'WhenMapped', 'Always']],
      ['sequenceNumber', Type::CARD16],
      ['replyLength', Type::CARD32],
      ['visualId', Type::VISUALID],
      ['class', Type::ENUM16, ['InputOutput', 'InputOnly']],
      ['bitGravity', Type::BYTE],
      ['winGravity', Type::BYTE],
      ['backingPlanes', Type::CARD32],
      ['backingPixel', Type::CARD32],
      ['saveUnder', Type::BOOL],
      ['mapIsInstalled', Type::BOOL],
      ['mapState', Type::ENUM8, ['Unmapped', 'Unviewable', 'Viewable']],
      ['overrideRedirect', Type::BOOL],
      ['colormap', Type::COLORMAP],
      ['allEventMasks', Type::CARD32],
      ['yourEventMask', Type::CARD32],
      ['doNotPropagateMask', Type::CARD16],
      ['unused', Type::UNUSED, 2]
    ]);
  }

}
