<?php

namespace X11;

class GetWindowAttributesRequest extends Request {

  public function __construct($window) {
    $this->doRequest([
      ['opcode', 3, Type::BYTE],
      ['unuset', 0, Type::BYTE],
      ['requestLength', 2, Type::CARD16],
      ['window', $window, Type::WINDOW]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function processResponse() {
    $response = $this->receiveResponse([
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
      ['unused', Type::CARD16]
    ]);
    return $response;
  }

}
