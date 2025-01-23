<?php

namespace X11;

class ConnectionInitRequest extends Request {

  public function __construct($byteOrder, $authorizationProtocolName = '', $authorizationProtocolData = '') {
    $apnlen = strlen($authorizationProtocolName);
    $apdlen = strlen($authorizationProtocolData);
    $this->sendRequest([
      ['byteOrder', $byteOrder, Type::BYTE],
      ['unused', 0, Type::BYTE],
      ['protocolMajorVersion', 11, Type::CARD16],
      ['protocolMinorVersion', 0, Type::CARD16],
      ['lengthOfAuthorizationProtocolName', $apnlen >> 2, Type::CARD16],
      ['lengthOfAuthorizationProtocolData', $apdlen >> 2, Type::CARD16],
      ['unused', 0, Type::CARD16],
      ['AuthorizationProtocolName', $authorizationProtocolName, Type::STRING8],
      ['AuthorizationProtocolData', $authorizationProtocolData, Type::STRING8]
    ]);
    Connection::setResponse($this->processResponse());
  }

  protected function connectionFailed() {
    $response = $this->receiveResponse([
      ['lengthOfReason', Type::BYTE],
      ['protocolMajorVersion', Type::CARD16],
      ['protocolMinorVersion', Type::CARD16],
      ['fullLength', Type::CARD16]
    ], false);
    $reason = $this->receiveResponse([
      ['reason', Type::STRING8, $response['lengthOfReason']]
    ], false);
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
    ], false);
    $vendor = $this->receiveResponse([
      ['vendor', Type::STRING8, $response['lengthOfVendor']]
    ], false);
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
      ], false);
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
      ], false);
      $depths = [];
      for ($j = 0; $j < $screen['numberOfDepths']; $j++) {
        $depth = $this->receiveResponse([
          ['depth', Type::CARD8],
          ['unused', Type::BYTE],
          ['numberOfVisualTypes', Type::CARD16],
          ['unused', Type::CARD32]
        ], false);
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
          ], false);
        }
        $depth['visualTypes'] = $visualTypes;
        $depths[] = $depth;
      }
      $screen['depths'] = $depths;
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
    ], false);
    $reason = $this->receiveResponse([
      ['reason', Type::STRING8, $response['length'] << 2]
    ], false);
    throw new \Exception("Authentication required: {$reason}");
  }

  protected function processResponse() {
    $status = $this->receiveResponse([
      ['status', Type::ENUM8, ['Failed', 'Success', 'Authenticate']]
    ], false);
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
