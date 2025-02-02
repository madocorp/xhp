<?php

namespace X11;

class ConnectionInitRequest extends Request {

  public function __construct($byteOrder, $authorizationProtocolName = '', $authorizationProtocolData = '') {
    $protocolMajorVersion = 11;
    $protocolMinorVersion = 0;
    $values = get_defined_vars();
    $this->sendRequest([
      ['byteOrder', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['protocolMajorVersion', Type::CARD16],
      ['protocolMinorVersion', Type::CARD16],
      ['lengthOfAuthorizationProtocolName', Type::LENGTH16_4, 'authorizationProtocolName'],
      ['lengthOfAuthorizationProtocolData', Type::LENGTH16_4, 'authorizationProtocolData'],
      ['unused', Type::UNUSED, 2],
      ['authorizationProtocolName', Type::STRING8],
      ['authorizationProtocolData', Type::STRING8]
    ], $values);
    Connection::setResponse($this->processResponse());
  }

  protected function connectionFailed() {
    $response = $this->receiveResponse([
      ['lengthOfReason', Type::CARD8],
      ['protocolMajorVersion', Type::CARD16],
      ['protocolMinorVersion', Type::CARD16],
      ['fullLength', Type::CARD16]
    ], false);
    $reason = $this->receiveResponse([
      ['reason', Type::STRING8, $response['lengthOfReason']]
    ], false);
    throw new \Exception("Connection failed: {$reason}");
    throw new \Exception("Connection failed: {$response['reason']}");
  }

  protected function connectionSuccess() {
    $response = $this->receiveResponse([
      ['unused', Type::UNUSED, 1],
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
      ['unused', Type::UNUSED, 4]
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
        ['unused', Type::UNUSED, 5]
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
          ['unused', Type::UNUSED, 1],
          ['numberOfVisualTypes', Type::CARD16],
          ['unused', Type::UNUSED, 4]
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
            ['unused', Type::UNUSED, 4],
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
      ['unused', Type::UNUSED, 5],
      ['length', Type::CARD16]
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
