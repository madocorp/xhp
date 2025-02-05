<?php

namespace X11;

define('X11\DEBUG', true);
require_once dirname(dirname(__FILE__)) . '/X11.php';

function eventHandler($event) {
  if ($event && $event['name'] == 'KeyPress') {
    if ($event['detail'] == 9) { // escape
      Event::end();
    }
  }
}

function zzz() {
  usleep(5000);
}

function errorHandler($errno, $errstr, $errfile, $errline) {
  var_dump($errno, $errstr, $errfile, $errline);
  exit(1);
}
set_error_handler('\X11\errorHandler');

Event::setHandler('\X11\eventHandler');

\X11\Connection::init();

$screen = \X11\Connection::$data['screens'][0];

$depth = $screen['rootDepth'];
$wid1 = \X11\Connection::generateId();
$root = $screen['root'];
$borderWidth = 0;
$alternaitveVisual = false;
foreach ($screen['depths'] as $adepth) {
  if ($adepth['depth'] == $depth) {
    foreach ($adepth['visualTypes'] as $avisual) {
      if ($avisual['class'] == 'DirectColor' && $avisual['colormapEntries'] == 256) {
        $alternativeVisual = $avisual['visualId'];
        break;
      }
    }
  }
}

$visual = $screen['rootVisual'];
new \X11\CreateWindowRequest(
  $depth, $wid1, $root, 0,
  0, 640, 480, $borderWidth,
  'InputOutput', $visual, ['backgroundPixel' => 0x000055, 'eventMask' => ['KeyPress', 'KeyRelease', 'Exposure']]
);
new \X11\ChangePropertyRequest('Replace', $wid1, Atom::WM_NAME, Atom::STRING, 8, 'Hello World 1!');
new \X11\MapWindowRequest($wid1);

new \X11\GetWindowAttributesRequest($wid1);
new \X11\GetPropertyRequest(false, $wid1, Atom::WM_NAME, Atom::STRING, 0, 100);

$wid2 = Connection::generateId();
new \X11\CreateWindowRequest(
  $depth, $wid2, $root, 0,
  0, 200, 200, $borderWidth,
  'InputOutput', $visual, ['backgroundPixel' => 0x550000, 'eventMask' => ['KeyPress', 'KeyRelease', 'Exposure']]
);
new \X11\ChangePropertyRequest('Replace', $wid2, Atom::WM_NAME, Atom::STRING, 8, 'Hello World 2!');
new \X11\MapWindowRequest($wid2);

zzz();
$swid1 = Connection::generateId();
new \X11\CreateWindowRequest(
  $depth, $swid1, $wid1, 10,
  10, 100, 100, 0,
  'InputOutput', $visual, ['backgroundPixel' => 0xaa0000]
);
$swid2 = Connection::generateId();
new \X11\CreateWindowRequest(
  $depth, $swid2, $wid1, 30,
  30, 100, 100, 0,
  'InputOutput', $visual, ['backgroundPixel' => 0xaaaa00]
);
$swid3 = Connection::generateId();
new \X11\CreateWindowRequest(
  $depth, $swid3, $wid1, 50,
  50, 100, 100, 5,
  'InputOutput', $visual, ['backgroundPixel' => 0x00aa00]
);
new \X11\MapSubwindowsRequest($wid1);

zzz();
new \X11\UnmapSubwindowsRequest($wid1);

zzz();
new \X11\MapSubwindowsRequest($wid1);

zzz();
new \X11\ConfigureWindowRequest($wid1, ['x' => 10, 'y' => 10]);

zzz();
new \X11\UnmapWindowRequest($wid2);

zzz();
new \X11\MapWindowRequest($wid2);

zzz();
new \X11\ConfigureWindowRequest($wid2, ['width' => 250, 'x' => 660, 'y' => 10]);

zzz();
new \X11\ChangeWindowAttributesRequest($swid3, ['borderPixel' => 0xffffff]);

zzz();
new \X11\CirculateWindowRequest('RaiseLowest', $wid1);

zzz();
new \X11\CirculateWindowRequest('RaiseLowest', $wid1);

zzz();
new \X11\CirculateWindowRequest('RaiseLowest', $wid1);

zzz();
new \X11\ChangeSaveSetRequest('Delete', $root); // ???

zzz();
new \X11\ReparentWindowRequest($swid2, $wid2, 50, 50);

zzz();
$gcid = Connection::generateId();
new \X11\CreateGCRequest($gcid, $wid1, ['background' => 0xffffff, 'foreground' => 0x0, 'lineWidth' => 3]);
new \X11\ChangeGCRequest($gcid, ['foreground' => 0xffff00]);

zzz();
$points = [];
for ($i = 0; $i < 100; $i++) {
  $points[] = ['x' => rand(150, 450), 'y' => rand(150, 450)];
}
new \X11\PolyPointRequest('Origin', $wid1, $gcid, $points);

zzz();
new \X11\PolyLineRequest('Origin', $wid1, $gcid, $points);

zzz();
new \X11\SetDashesRequest($gcid, 0, [8, 5, 3, 2, 1]);
new \X11\ChangeGCRequest($gcid, ['lineStyle' => 'OnOffDash']);
new \X11\PolyRectangleRequest($wid1, $gcid, [['x' => 500, 'y' => 400, 'width' => 100, 'height' => 50]]);
new \X11\ChangeGCRequest($gcid, ['lineStyle' => 'Solid']);

zzz();
new \X11\PolySegmentRequest($wid1, $gcid, [['x1' => 500, 'y1' => 400, 'x2' => 550, 'y2' => 450]]);

zzz();
new \X11\PolyArcRequest($wid1, $gcid, [['x' => 500, 'y' => 400, 'width' => 100, 'height' => 50, 'angle1' => 0, 'angle2' => 180 * 64]]);

zzz();
new \X11\PolyFillRectangleRequest($wid1, $gcid, [['x' => 200, 'y' => 400, 'width' => 100, 'height' => 50]]);

zzz();
new \X11\PolyFillArcRequest($wid1, $gcid, [['x' => 500, 'y' => 100, 'width' => 100, 'height' => 50, 'angle1' => 0, 'angle2' => 180 * 64]]);

zzz();
new \X11\SetClipRectanglesRequest('UnSorted', $gcid, 0, 0, [['x' => 100, 'y' => 0, 'width' => 80, 'height' => 100]]);
new \X11\FillPolyRequest($wid1, $gcid, 'Convex', 'Origin', [['x' => 100, 'y' => 0], ['x' => 200, 'y' => 0], ['x' => 150, 'y' => 50]]);
new \X11\SetClipRectanglesRequest('UnSorted', $gcid, 0, 0, [['x' => 0, 'y' => 0, 'width' => 640, 'height' => 480]]);

zzz();
new \X11\ClearAreaRequest(false, $wid1, 200, 200, 200, 200);

zzz();
new \X11\ListFontsRequest(10, '*fixed*iso8859-2');
$fonts = Connection::getLastResponse();
$fonta = $fonts['fonts'][0];
new \X11\ListFontsRequest(10, '*fixed*iso10646-1');
$fonts = Connection::getLastResponse();
$fontb = $fonts['fonts'][0];
new \X11\ListFontsRequest(10, '*helvetica*iso8859-2');
$fonts = Connection::getLastResponse();
$fontc = $fonts['fonts'][0];
new \X11\ListFontsWithInfoRequest(2, '*fixed*');
$fid1 = Connection::generateId();
new \X11\OpenFontRequest($fid1, $fonta);
$fid2 = Connection::generateId();
new \X11\OpenFontRequest($fid2, $fontb);
$fid4 = Connection::generateId();
new \X11\OpenFontRequest($fid4, $fontc);

$fid3 = Connection::generateId();
new \X11\OpenFontRequest($fid3, 'cursor');
new \X11\ChangeGCRequest($gcid, ['font' => $fid1, 'background' => 0x550000]);
new \X11\ImageText8Request($wid2, $gcid, 10, 10, mb_convert_encoding('Hello Wőrld!','ISO8859-2','UTF-8'));
new \X11\ChangeGCRequest($gcid, ['font' => $fid2, 'background' => 0x550000]);
new \X11\ImageText16Request($wid2, $gcid, 10, 30, 'Hélló Wőrld!');
new \X11\PolyText8Request($wid1, $gcid, 10, 400, [[0, 'ABC'], $fid4, [0, 'DEF']]);
new \X11\PolyText16Request($wid1, $gcid, 10, 440, [[0, 'FGH'], $fid2, [10, 'IJK']]);

zzz();
$pmid1 = Connection::generateId();
new \X11\CreatePixmapRequest(1, $pmid1, $wid1, 32, 32);
$pmid2 = Connection::generateId();
new \X11\CreatePixmapRequest(1, $pmid2, $wid1, 32, 32);
$gcid2 = Connection::generateId();
new \X11\CreateGCRequest($gcid2, $pmid1, []);
$gcid3 = Connection::generateId();
new \X11\CreateGCRequest($gcid3, $pmid1, []);
new \X11\ChangeGCRequest($gcid2, ['foreground' => 1, 'background' => 0]);
new \X11\CopyGCRequest($gcid2, $gcid3, ['foreground', 'background']);
new \X11\ChangeGCRequest($gcid2, ['foreground' => 0, 'background' => 1]);
new \X11\PolyFillRectangleRequest($pmid2, $gcid3, [['x' => 0, 'y' => 0, 'width' => 32, 'height' => 32]]);
new \X11\PolyFillRectangleRequest($pmid1, $gcid3, [['x' => 0, 'y' => 0, 'width' => 32, 'height' => 32]]);
new \X11\PolyFillRectangleRequest($pmid1, $gcid2, [['x' => 0, 'y' => 0, 'width' => 16, 'height' => 16]]);
new \X11\PolyFillRectangleRequest($pmid2, $gcid2, [['x' => 8, 'y' => 8, 'width' => 16, 'height' => 16]]);
$crid1 = Connection::generateId();
new \X11\CreateCursorRequest($crid1, $pmid1, $pmid2, 0xffff, 0xffff, 0x0, 0xffff, 0x0, 0x0, 16, 16);
new \X11\ChangeWindowAttributesRequest($wid1, ['cursor' => $crid1]);
$crid2 = Connection::generateId();
new \X11\CreateGlyphCursorRequest($crid2, $fid3, $fid3, 122, 122, 0x0, 0x0, 0x0, 0x0, 0x0, 0x0);
new \X11\ChangeWindowAttributesRequest($wid2, ['cursor' => $crid2]);

zzz();
$pmid3 = Connection::generateId();
new \X11\CreatePixmapRequest(24, $pmid3, $root, 100, 100);
new \X11\PutImageRequest('ZPixmap', $pmid3, $gcid, 100, 100, 0, 0, 0, 24, pack("C*", ...array_fill(0, 4*100*100, 0xff)));
new \X11\PolyArcRequest($pmid3, $gcid, [['x' => 10, 'y' => 10, 'width' => 80, 'height' => 80, 'angle1' => 0, 'angle2' => 360 * 64]]);
new \X11\CopyAreaRequest($pmid3, $wid1, $gcid, 0, 0, 300, 300, 100, 100);
new \X11\CopyPlaneRequest($pmid2, $pmid1, $gcid2, 0, 0, 0, 0, 16, 16, 1);

zzz();
new \X11\RecolorCursorRequest($crid2,  0xffff, 0xaaaa, 0x8888, 0x0, 0x0, 0x0);

zzz();
new \X11\GetImageRequest('ZPixmap', $wid2, 0, 0, 100, 100, 0xffffffff);
$image = Connection::getLastResponse();
new \X11\PutImageRequest('ZPixmap', $wid1, $gcid, 100, 100, 540, 380, 0, 24, $image['data']);

zzz();
new \X11\ChangeHostsRequest('Insert', 'Internet', pack('CCCC', 0xaa, 0, 0, 0));

zzz();
new \X11\ListHostsRequest();

zzz();
new \X11\ListExtensionsRequest();
new \X11\QueryExtensionRequest('BIG-REQUESTS');

zzz();
new \X11\ListPropertiesRequest($wid1);
$res = Connection::getLastResponse();
new \X11\RotatePropertiesRequest($wid1, 2, [$res['atoms'][0], $res['atoms'][1]]);

zzz();
new \X11\GetAtomNameRequest(Atom::CURSOR);

zzz();
new \X11\GetFontPathRequest();

zzz();
new \X11\QueryFontRequest($fid1);

zzz();
new \X11\QueryKeymapRequest();

zzz();
new \X11\QueryPointerRequest($wid1);

zzz();
new \X11\QueryTextExtentsRequest($fid2, "Lorem ipsum...");

zzz();
new \X11\QueryTreeRequest($wid1);

zzz();
new \X11\GetGeometryRequest($root);

zzz();
new \X11\WarpPointerRequest(0, $wid2, 100, 100, 0, 0, 0, 0);

zzz();
new \X11\GrabPointerRequest(true, $wid2, ['ButtonPress'], 'Asynchronous', 'Asynchronous', $wid2, $crid2, 0);

new \X11\ChangeActivePointerGrabRequest($crid2, 0, ['ButtonPress']);
new \X11\UngrabPointerRequest(0);

new \X11\GrabButtonRequest(true, $root, ['EnterWindow'], 'Asynchronous', 'Asynchronous', 0, 0, 1, 1);
new \X11\UngrabButtonRequest(1, $root, 0);

new \X11\GrabKeyboardRequest(true, $wid1, 0, 'Asynchronous', 'Asynchronous');
new \X11\UngrabKeyboardRequest(0);

new \X11\GrabKeyRequest(true, $root, 0, 130, 'Asynchronous', 'Asynchronous');
new \X11\UngrabKeyRequest(130, $wid1, 0);

new \X11\GrabServerRequest();
new \X11\UngrabServerRequest();

new \X11\KillClientRequest(0);

zzz();

zzz();
new \X11\AllowEventsRequest('AsyncPointer', time());


zzz();
new \X11\SetScreenSaverRequest(0, 400, 'No', 'No');

zzz();
new \X11\GetScreenSaverRequest();

zzz();
new \X11\ForceScreenSaverRequest('Reset');

zzz();
new \X11\QueryBestSizeRequest('Cursor', $root, 1000, 1000);

if ($alternativeVisual !== false) {
  zzz();
  $cmid1 = Connection::generateId();
  new \X11\CreateColormapRequest('All', $cmid1, $wid2, $alternativeVisual);
  $colors = [];
  for ($i = 0; $i < 256; $i++) {
    $color = [
      'pixel' => $i,
      'red' =>  rand(0, 0xffff),
      'green' => rand(0, 0xffff),
      'blue' => rand(0, 0xffff),
      'doColors' => 0x7,
      'pad' => 0
    ];
    $colors[] = $color;
  }
  new \X11\StoreColorsRequest($cmid1, $colors);
  new \X11\StoreNamedColorRequest(0x1, $cmid1, 0xff, 'Red');

  new \X11\InstallColormapRequest($cmid1);

  zzz();
  new \X11\QueryColorsRequest($cmid1, [['pixel' => 0x1], ['pixel' => 0xaa00aa], ['pixel' => 0x00ffff]]);

  zzz();
  new \X11\ListInstalledColormapsRequest($wid2);

  zzz();
  new \X11\UninstallColormapRequest($cmid1);

  zzz();
  new \X11\LookupColorRequest($cmid1, 'red');

  zzz();
  $cmid2 = Connection::generateId();
  new \X11\CreateColormapRequest('None', $cmid2, $wid2, $alternativeVisual);

  zzz();
  new \X11\AllocColorRequest($cmid2, 0xff, 0xff, 0x0);

  zzz();
  new \X11\AllocNamedColorRequest($cmid2, 'blue');
  $response = Connection::getLastResponse();
  $pixel = $response['pixel'];

  zzz();
  new \X11\AllocColorCellsRequest(false, $cmid2, 2, 1);

  zzz();
  new \X11\AllocColorPlanesRequest(false, $cmid2, 2, 0, 0, 0);

  zzz();
  new \X11\FreeColorsRequest($cmid2, 0, [['pixel' => $pixel]]);

  $cmid3 = Connection::generateId();
  new \X11\CopyColormapAndFreeRequest($cmid3, $cmid2);

  zzz();
  new \X11\FreeColormapRequest($cmid2);
}


zzz();
new \X11\DeletePropertyRequest($wid1, Atom::WM_NAME);

zzz();
new \X11\GetKeyboardControlRequest();
new \X11\ChangeKeyboardControlRequest(['led' => 2, 'ledMode' => 'On']);

zzz();
new \X11\ChangeKeyboardControlRequest(['led' => 2, 'ledMode' => 'Off']);

zzz();
new \X11\ChangeKeyboardMappingRequest(38, 4, [[98, 66, 98, 66]]);

zzz();
new \X11\GetKeyboardMappingRequest(38, 1);

zzz();
new \X11\ChangeKeyboardMappingRequest(38, 4, [[97, 65, 97, 65]]);

zzz();
new \X11\GetPointerControlRequest();
$pointerControl = Connection::getLastResponse();
new \X11\ChangePointerControlRequest(2, 3, 30, true, true);
new \X11\GetPointerControlRequest();
new \X11\ChangePointerControlRequest($pointerControl['accelerationNumerator'], $pointerControl['accelerationDenominator'], $pointerControl['thresold'], true, true);

zzz();
new \X11\InternAtomRequest(true, 'CLIPBOARD');
$clipboard = Connection::getLastResponse();
new \X11\InternAtomRequest(true, 'XSEL_DATA');
$xseldata = Connection::getLastResponse();

new \X11\GetSelectionOwnerRequest($clipboard['atom']);
new \X11\ConvertSelectionRequest($root, $clipboard['atom'], $clipboard['atom'], $clipboard['atom'], 0);

zzz();
new \X11\GetInputFocusRequest();
new \X11\GetModifierMappingRequest();
new \X11\GetPointerMappingRequest();
$pmap = Connection::getLastResponse();
new \X11\GetMotionEventsRequest($wid1, time() - 1000, 0);

zzz();
new \X11\SendEventRequest(false, $wid1, ['MappingNotify'], Event::arrayToBytes('MappingNotify', ['count' => 1, 'state' => 'Modifier', 'sequenceNumber' => 1]));

zzz();
new \X11\SetAccessControlRequest('Enable');
new \X11\SetCloseDownModeRequest('Destroy');
new \X11\SetFontPathRequest(['/usr/share/fonts/X11/misc']);
new \X11\SetFontPathRequest([]);
new \X11\SetSelectionOwnerRequest($wid1, $clipboard['atom'] , 0);

zzz();
new \X11\SetInputFocusRequest('None', $wid1, 0);

zzz();
new \X11\SetModifierMappingRequest(2, [[50, 62], [66, 0], [37, 105], [64, 204],  [77, 0], [203, 0], [133, 134], [92, 0]]);

zzz();
new \X11\SetPointerMappingRequest(range(1, $pmap['n']));

zzz();
new \X11\TranslateCoordinatesRequest($wid1, $wid2, 0, 0);

zzz();
new \X11\NoOperationRequest(16);

zzz();
new \X11\BellRequest(50);

Event::loop();


new \X11\FreeGCRequest($gcid);
new \X11\FreePixmapRequest($pmid1);
new \X11\FreeCursorRequest($crid1);
new \X11\CloseFontRequest($fid1);

new \X11\DestroySubwindowsRequest($wid1);

zzz();
new \X11\DestroyWindowRequest($wid1);

zzz();
new \X11\DestroyWindowRequest($wid2);
