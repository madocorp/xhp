<?php

namespace X11;

class ConnectionInitRequest extends Request {

  public function __construct($byteOrder, $authorizationProtocolName = '', $authorizationProtocolData = '') {
    $apnlen = strlen($authorizationProtocolName);
    $apnpad = Connection::pad4($apnlen);
    $apdlen = strlen($authorizationProtocolData);
    $apdpad = Connection::pad4($apdlen);
    $this->doRequest([
      ['byteOrder', $byteOrder, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['protocolMajorVersion', 11, Type::CARD16],
      ['protocolMinorVersion', 0, Type::CARD16],
      ['lengthOfAuthorizationProtocolName', ($apnlen + $apnpad) >> 2, Type::CARD16],
      ['lengthOfAuthorizationProtocolData', ($apdlen + $apdpad) >> 2, Type::CARD16],
      ['unused', 0, Type::CARD16],
      ['AuthorizationProtocolName', $authorizationProtocolName, Type::STRING8],
      ['pad', $apnpad, Type::PAD4],
      ['AuthorizationProtocolData', $authorizationProtocolData, Type::STRING8],
      ['pad', $apdpad, Type::PAD4]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function connectionFailed() {
    $response = $this->receiveResponse([
      ['lengthOfReason', Type::BYTE],
      ['protocolMajorVersion', Type::CARD16],
      ['protocolMinorVersion', Type::CARD16],
      ['fullLength', Type::CARD16]
    ]);
    $reason = $this->receiveResponse([
      ['reason', Type::STRING8, $response['lengthOfReason']]
    ]);
    throw new \Exception("Connection failed: {$reason}");
  }

  protected function connectionSuccess() {
    $response = $this->receiveResponse([
      ['unused', Type::BYTE],
      ['protocolMajorVersion', Type::CARD16],
      ['protocolMinorVersion', Type::CARD16],
      ['additionalDataLength', Type::CARD16],
      ['releaseNumber', Type::CARD32],
      ['resourceIdBase', Type::CARD32],
      ['resourceIdMask', Type::CARD32],
      ['motionBufferSize', Type::CARD32],
      ['lengthOfVendor', Type::CARD16],
      ['maximumRequestLength', Type::CARD16],
      ['numberOfScreens', Type::CARD8],
      ['numberOfFormats', Type::CARD8],
      ['imageByteOrder', Type::ENUM8, ['LSBFirst', 'MSBFirst']],
      ['bitmapFormatBitOrder', Type::ENUM8, ['LeastSignificant', 'MostSignificant']],
      ['bitmapFormatScanlineUnit', Type::CARD8],
      ['bitmapFormatScanlinePad', Type::CARD8],
      ['minKeycode', Type::CARD8],
      ['maxKeycode', Type::CARD8],
      ['unused', Type::CARD32]
    ]);
    $vendor = $this->receiveResponse([
      ['vendor', Type::STRING8, $response['lengthOfVendor']]
    ]);
    $formats = [];
    for ($i = 0; $i < $response['numberOfFormats']; $i++) {
      $formats[] = $this->receiveResponse([
        ['depth', Type::CARD8],
        ['bitsPerPixel', Type::CARD8],
        ['scanlinePad', Type::CARD8],
        ['unused', Type::BYTE],
        ['unused', Type::BYTE],
        ['unused', Type::BYTE],
        ['unused', Type::BYTE],
        ['unused', Type::BYTE],
      ]);
    }
    $screens = [];
    for ($i = 0; $i < $response['numberOfScreens']; $i++) {
      $screen = $this->receiveResponse([
        ['root', Type::WINDOW],
        ['defaultColormap', Type::COLORMAP],
        ['whitePixel', Type::CARD32],
        ['blackPixel', Type::CARD32],
        ['currentInputMasks', Type::CARD32],
        ['widthInPixels', Type::CARD16],
        ['heightInPixels', Type::CARD16],
        ['widthInMilimeters', Type::CARD16],
        ['heightInMilimeters', Type::CARD16],
        ['minInstalledMaps', Type::CARD16],
        ['maxInstalledMaps', Type::CARD16],
        ['rootVisual', Type::VISUALID],
        ['backingStores', Type::ENUM8, ['Never', 'WhenMapped', 'Always']],
        ['saveUnders', Type::BOOL],
        ['rootDepth', Type::CARD8],
        ['numberOfDepths', Type::CARD8]
      ]);
      $depths = [];
      for ($j = 0; $j < $screen['numberOfDepths']; $j++) {
        $depth = $this->receiveResponse([
          ['depth', Type::CARD8],
          ['unused', Type::BYTE],
          ['numberOfVisualTypes', Type::CARD16],
          ['unused', Type::CARD32]
        ]);
        $visualsTypes = [];
        for ($k = 0; $k < $depth['numberOfVisualTypes']; $k++) {
          $visualTypes[] = $this->receiveResponse([
            ['visualId', Type::VISUALID],
            ['class', Type::ENUM8, ['StaticGray', 'GrayScale', 'StaticColor', 'PseudoColor', 'TrueColor', 'DirectColor']],
            ['bitsPerRgbValue', Type::CARD8],
            ['colormapEntries', Type::CARD16],
            ['redMask', Type::CARD32],
            ['greenMask', Type::CARD32],
            ['blueMask', Type::CARD32],
            ['unused', Type::CARD32],
          ]);
        }
        $depth['visualTypes'] = $visualTypes;
        $depths[] = $depth;
      }
      $screen['depths'] = $depth;
      $screens[] = $screen;
    }
    $response['screens'] = $screens;
    return $response;
  }

  protected function connectionAuthenticate() {
    $response = $this->receiveResponse([
      ['unused', Type::BYTE],
      ['unused', Type::BYTE],
      ['unused', Type::BYTE],
      ['unused', Type::BYTE],
      ['unused', Type::BYTE],
      ['length', Type::CARD16],
    ]);
    $reason = $this->receiveResponse([
      ['reason', Type::STRING8, $response['length'] << 2]
    ]);
    throw new \Exception("Authentication required: {$reason}");
  }

  protected function processResponse() {
    $status = $this->receiveResponse([
      ['status', Type::ENUM8, ['Failed', 'Success', 'Authenticate']]
    ]);
    switch ($status) {
      case 'Failed':
        return $this->connectionFailed();
      case 'Success':
        return $this->connectionSuccess();
      case 'Authenticate':
        return $this->connectionAuthenticate();
    }
  }

}
