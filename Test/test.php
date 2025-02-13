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

Connection::init();

$screen = Connection::$data['screens'][0];

$depth = $screen['rootDepth'];
$wid1 = Connection::generateId();
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
new CreateWindowRequest(
  $depth, $wid1, $root, 0,
  0, 640, 480, $borderWidth,
  'InputOutput', $visual, ['backgroundPixel' => 0x000055, 'eventMask' => ['KeyPress', 'KeyRelease', 'Exposure']]
);
new ChangePropertyRequest('Replace', $wid1, Atom::WM_NAME, Atom::STRING, 8, 'Hello World 1!');
new MapWindowRequest($wid1);

new GetWindowAttributesRequest($wid1);
new GetPropertyRequest(false, $wid1, Atom::WM_NAME, Atom::STRING, 0, 100);

$wid2 = Connection::generateId();
new CreateWindowRequest(
  $depth, $wid2, $root, 0,
  0, 200, 200, $borderWidth,
  'InputOutput', $visual, ['backgroundPixel' => 0x550000, 'eventMask' => ['KeyPress', 'KeyRelease', 'Exposure']]
);
new ChangePropertyRequest('Replace', $wid2, Atom::WM_NAME, Atom::STRING, 8, 'Hello World 2!');
new MapWindowRequest($wid2);

zzz();
$swid1 = Connection::generateId();
new CreateWindowRequest(
  $depth, $swid1, $wid1, 10,
  10, 100, 100, 0,
  'InputOutput', $visual, ['backgroundPixel' => 0xaa0000]
);
$swid2 = Connection::generateId();
new CreateWindowRequest(
  $depth, $swid2, $wid1, 30,
  30, 100, 100, 0,
  'InputOutput', $visual, ['backgroundPixel' => 0xaaaa00]
);
$swid3 = Connection::generateId();
new CreateWindowRequest(
  $depth, $swid3, $wid1, 50,
  50, 100, 100, 5,
  'InputOutput', $visual, ['backgroundPixel' => 0x00aa00]
);
new MapSubwindowsRequest($wid1);

zzz();
new UnmapSubwindowsRequest($wid1);

zzz();
new MapSubwindowsRequest($wid1);

zzz();
new ConfigureWindowRequest($wid1, ['x' => 10, 'y' => 10]);

zzz();
new UnmapWindowRequest($wid2);

zzz();
new MapWindowRequest($wid2);

zzz();
new ConfigureWindowRequest($wid2, ['width' => 250, 'x' => 660, 'y' => 10]);

zzz();
new ChangeWindowAttributesRequest($swid3, ['borderPixel' => 0xffffff]);

zzz();
new CirculateWindowRequest('RaiseLowest', $wid1);

zzz();
new CirculateWindowRequest('RaiseLowest', $wid1);

zzz();
new CirculateWindowRequest('RaiseLowest', $wid1);

zzz();
new ChangeSaveSetRequest('Delete', $root); // ???

zzz();
new ReparentWindowRequest($swid2, $wid2, 50, 50);

zzz();
$gcid = Connection::generateId();
new CreateGCRequest($gcid, $wid1, ['background' => 0xffffff, 'foreground' => 0x0, 'lineWidth' => 3]);
new ChangeGCRequest($gcid, ['foreground' => 0xffff00]);

zzz();
$points = [];
for ($i = 0; $i < 100; $i++) {
  $points[] = ['x' => rand(150, 450), 'y' => rand(150, 450)];
}
new PolyPointRequest('Origin', $wid1, $gcid, $points);

zzz();
new PolyLineRequest('Origin', $wid1, $gcid, $points);

zzz();
new SetDashesRequest($gcid, 0, [8, 5, 3, 2, 1]);
new ChangeGCRequest($gcid, ['lineStyle' => 'OnOffDash']);
new PolyRectangleRequest($wid1, $gcid, [['x' => 500, 'y' => 400, 'width' => 100, 'height' => 50]]);
new ChangeGCRequest($gcid, ['lineStyle' => 'Solid']);

zzz();
new PolySegmentRequest($wid1, $gcid, [['x1' => 500, 'y1' => 400, 'x2' => 550, 'y2' => 450]]);

zzz();
new PolyArcRequest($wid1, $gcid, [['x' => 500, 'y' => 400, 'width' => 100, 'height' => 50, 'angle1' => 0, 'angle2' => 180 * 64]]);

zzz();
new PolyFillRectangleRequest($wid1, $gcid, [['x' => 200, 'y' => 400, 'width' => 100, 'height' => 50]]);

zzz();
new PolyFillArcRequest($wid1, $gcid, [['x' => 500, 'y' => 100, 'width' => 100, 'height' => 50, 'angle1' => 0, 'angle2' => 180 * 64]]);

zzz();
new SetClipRectanglesRequest('UnSorted', $gcid, 0, 0, [['x' => 100, 'y' => 0, 'width' => 80, 'height' => 100]]);
new FillPolyRequest($wid1, $gcid, 'Convex', 'Origin', [['x' => 100, 'y' => 0], ['x' => 200, 'y' => 0], ['x' => 150, 'y' => 50]]);
new SetClipRectanglesRequest('UnSorted', $gcid, 0, 0, [['x' => 0, 'y' => 0, 'width' => 640, 'height' => 480]]);

zzz();
new ClearAreaRequest(false, $wid1, 200, 200, 200, 200);

zzz();
new ListFontsRequest(10, '*fixed*iso8859-2');
$fonts = Connection::getLastResponse();
$fonta = $fonts['fonts'][0];
new ListFontsRequest(10, '*fixed*iso10646-1');
$fonts = Connection::getLastResponse();
$fontb = $fonts['fonts'][0];
new ListFontsRequest(10, '*helvetica*iso8859-2');
$fonts = Connection::getLastResponse();
$fontc = $fonts['fonts'][0];
new ListFontsWithInfoRequest(2, '*fixed*');
$fid1 = Connection::generateId();
new OpenFontRequest($fid1, $fonta);
$fid2 = Connection::generateId();
new OpenFontRequest($fid2, $fontb);
$fid4 = Connection::generateId();
new OpenFontRequest($fid4, $fontc);

$fid3 = Connection::generateId();
new OpenFontRequest($fid3, 'cursor');
new ChangeGCRequest($gcid, ['font' => $fid1, 'background' => 0x550000]);
new ImageText8Request($wid2, $gcid, 10, 10, mb_convert_encoding('Hello Wőrld!','ISO8859-2','UTF-8'));
new ChangeGCRequest($gcid, ['font' => $fid2, 'background' => 0x550000]);
new ImageText16Request($wid2, $gcid, 10, 30, 'Hélló Wőrld!');
new PolyText8Request($wid1, $gcid, 10, 400, [[0, 'ABC'], $fid4, [0, 'DEF']]);
new PolyText16Request($wid1, $gcid, 10, 440, [[0, 'FGH'], $fid2, [10, 'IJK']]);

zzz();
$pmid1 = Connection::generateId();
new CreatePixmapRequest(1, $pmid1, $wid1, 32, 32);
$pmid2 = Connection::generateId();
new CreatePixmapRequest(1, $pmid2, $wid1, 32, 32);
$gcid2 = Connection::generateId();
new CreateGCRequest($gcid2, $pmid1, []);
$gcid3 = Connection::generateId();
new CreateGCRequest($gcid3, $pmid1, []);
new ChangeGCRequest($gcid2, ['foreground' => 1, 'background' => 0]);
new CopyGCRequest($gcid2, $gcid3, ['foreground', 'background']);
new ChangeGCRequest($gcid2, ['foreground' => 0, 'background' => 1]);
new PolyFillRectangleRequest($pmid2, $gcid3, [['x' => 0, 'y' => 0, 'width' => 32, 'height' => 32]]);
new PolyFillRectangleRequest($pmid1, $gcid3, [['x' => 0, 'y' => 0, 'width' => 32, 'height' => 32]]);
new PolyFillRectangleRequest($pmid1, $gcid2, [['x' => 0, 'y' => 0, 'width' => 16, 'height' => 16]]);
new PolyFillRectangleRequest($pmid2, $gcid2, [['x' => 8, 'y' => 8, 'width' => 16, 'height' => 16]]);
$crid1 = Connection::generateId();
new CreateCursorRequest($crid1, $pmid1, $pmid2, 0xffff, 0xffff, 0x0, 0xffff, 0x0, 0x0, 16, 16);
new ChangeWindowAttributesRequest($wid1, ['cursor' => $crid1]);
$crid2 = Connection::generateId();
new CreateGlyphCursorRequest($crid2, $fid3, $fid3, 122, 122, 0x0, 0x0, 0x0, 0x0, 0x0, 0x0);
new ChangeWindowAttributesRequest($wid2, ['cursor' => $crid2]);

zzz();
$pmid3 = Connection::generateId();
new CreatePixmapRequest(24, $pmid3, $root, 100, 100);
new PutImageRequest('ZPixmap', $pmid3, $gcid, 100, 100, 0, 0, 0, 24, pack("C*", ...array_fill(0, 4*100*100, 0xff)));
new PolyArcRequest($pmid3, $gcid, [['x' => 10, 'y' => 10, 'width' => 80, 'height' => 80, 'angle1' => 0, 'angle2' => 360 * 64]]);
new CopyAreaRequest($pmid3, $wid1, $gcid, 0, 0, 300, 300, 100, 100);
new CopyPlaneRequest($pmid2, $pmid1, $gcid2, 0, 0, 0, 0, 16, 16, 1);

zzz();
new RecolorCursorRequest($crid2,  0xffff, 0xaaaa, 0x8888, 0x0, 0x0, 0x0);

zzz();
new GetImageRequest('ZPixmap', $wid2, 0, 0, 100, 100, 0xffffffff);
$image = Connection::getLastResponse();
new PutImageRequest('ZPixmap', $wid1, $gcid, 100, 100, 540, 380, 0, 24, $image['data']);

zzz();
new ChangeHostsRequest('Insert', 'Internet', pack('CCCC', 0xaa, 0, 0, 0));

zzz();
new ListHostsRequest();

zzz();
new ListExtensionsRequest();
new QueryExtensionRequest('BIG-REQUESTS');

zzz();
new ListPropertiesRequest($wid1);
$res = Connection::getLastResponse();
new RotatePropertiesRequest($wid1, 2, [$res['atoms'][0], $res['atoms'][1]]);

zzz();
new GetAtomNameRequest(Atom::CURSOR);

zzz();
new GetFontPathRequest();

zzz();
new QueryFontRequest($fid1);

zzz();
new QueryKeymapRequest();

zzz();
new QueryPointerRequest($wid1);

zzz();
new QueryTextExtentsRequest($fid2, "Lorem ipsum...");

zzz();
new QueryTreeRequest($wid1);

zzz();
new GetGeometryRequest($root);

zzz();
new WarpPointerRequest(0, $wid2, 100, 100, 0, 0, 0, 0);

zzz();
new GrabPointerRequest(true, $wid2, ['ButtonPress'], 'Asynchronous', 'Asynchronous', $wid2, $crid2, 0);

new ChangeActivePointerGrabRequest($crid2, 0, ['ButtonPress']);
new UngrabPointerRequest(0);

new GrabButtonRequest(true, $root, ['EnterWindow'], 'Asynchronous', 'Asynchronous', 0, 0, 1, 1);
new UngrabButtonRequest(1, $root, 0);

new GrabKeyboardRequest(true, $wid1, 0, 'Asynchronous', 'Asynchronous');
new UngrabKeyboardRequest(0);

new GrabKeyRequest(true, $root, 0, 130, 'Asynchronous', 'Asynchronous');
new UngrabKeyRequest(130, $wid1, 0);

new GrabServerRequest();
new UngrabServerRequest();

new KillClientRequest(0);

zzz();

zzz();
new AllowEventsRequest('AsyncPointer', time());


zzz();
new SetScreenSaverRequest(0, 400, 'No', 'No');

zzz();
new GetScreenSaverRequest();

zzz();
new ForceScreenSaverRequest('Reset');

zzz();
new QueryBestSizeRequest('Cursor', $root, 1000, 1000);

if ($alternativeVisual !== false) {
  zzz();
  $cmid1 = Connection::generateId();
  new CreateColormapRequest('All', $cmid1, $wid2, $alternativeVisual);
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
  new StoreColorsRequest($cmid1, $colors);
  new StoreNamedColorRequest(0x1, $cmid1, 0xff, 'Red');

  new InstallColormapRequest($cmid1);

  zzz();
  new QueryColorsRequest($cmid1, [['pixel' => 0x1], ['pixel' => 0xaa00aa], ['pixel' => 0x00ffff]]);

  zzz();
  new ListInstalledColormapsRequest($wid2);

  zzz();
  new UninstallColormapRequest($cmid1);

  zzz();
  new LookupColorRequest($cmid1, 'red');

  zzz();
  $cmid2 = Connection::generateId();
  new CreateColormapRequest('None', $cmid2, $wid2, $alternativeVisual);

  zzz();
  new AllocColorRequest($cmid2, 0xff, 0xff, 0x0);

  zzz();
  new AllocNamedColorRequest($cmid2, 'blue');
  $response = Connection::getLastResponse();
  $pixel = $response['pixel'];

  zzz();
  new AllocColorCellsRequest(false, $cmid2, 2, 1);

  zzz();
  new AllocColorPlanesRequest(false, $cmid2, 2, 0, 0, 0);

  zzz();
  new FreeColorsRequest($cmid2, 0, [['pixel' => $pixel]]);

  $cmid3 = Connection::generateId();
  new CopyColormapAndFreeRequest($cmid3, $cmid2);

  zzz();
  new FreeColormapRequest($cmid2);
}


zzz();
new DeletePropertyRequest($wid1, Atom::WM_NAME);

zzz();
new GetKeyboardControlRequest();
new ChangeKeyboardControlRequest(['led' => 2, 'ledMode' => 'On']);

zzz();
new ChangeKeyboardControlRequest(['led' => 2, 'ledMode' => 'Off']);

zzz();
new ChangeKeyboardMappingRequest(38, 4, [[98, 66, 98, 66]]);

zzz();
new GetKeyboardMappingRequest(38, 1);

zzz();
new ChangeKeyboardMappingRequest(38, 4, [[97, 65, 97, 65]]);

zzz();
new GetPointerControlRequest();
$pointerControl = Connection::getLastResponse();
new ChangePointerControlRequest(2, 3, 30, true, true);
new GetPointerControlRequest();
new ChangePointerControlRequest($pointerControl['accelerationNumerator'], $pointerControl['accelerationDenominator'], $pointerControl['thresold'], true, true);

zzz();
new InternAtomRequest(true, 'CLIPBOARD');
$clipboard = Connection::getLastResponse();
new InternAtomRequest(true, 'XSEL_DATA');
$xseldata = Connection::getLastResponse();

new GetSelectionOwnerRequest($clipboard['atom']);
new ConvertSelectionRequest($root, $clipboard['atom'], $clipboard['atom'], $clipboard['atom'], 0);

zzz();
new GetInputFocusRequest();
new GetModifierMappingRequest();
new GetPointerMappingRequest();
$pmap = Connection::getLastResponse();
new GetMotionEventsRequest($wid1, time() - 1000, 0);

zzz();
new SendEventRequest(false, $wid1, ['MappingNotify'], Event::arrayToBytes('MappingNotify', ['count' => 1, 'state' => 'Modifier', 'sequenceNumber' => 1]));

zzz();
new SetAccessControlRequest('Enable');
new SetCloseDownModeRequest('Destroy');
new SetFontPathRequest(['/usr/share/fonts/X11/misc']);
new SetFontPathRequest([]);
new SetSelectionOwnerRequest($wid1, $clipboard['atom'] , 0);

zzz();
new SetInputFocusRequest('None', $wid1, 0);

zzz();
new SetModifierMappingRequest(2, [[50, 62], [66, 0], [37, 105], [64, 204],  [77, 0], [203, 0], [133, 134], [92, 0]]);

zzz();
new SetPointerMappingRequest(range(1, $pmap['n']));

zzz();
new TranslateCoordinatesRequest($wid1, $wid2, 0, 0);

zzz();
new NoOperationRequest(16);

zzz();
new BellRequest(50);

zzz();
// big request
$img = imagecreatetruecolor(640, 480);
$bg = imagecolorallocate($img, 255, 255, 0);
imagefill($img, 0, 0, $bg);
$fg = imagecolorallocate($img, 255, 0, 0);
imagefilledellipse($img, 320, 240, 600, 400, $fg);
$data = [];
for ($i = 0; $i < 480; $i++) {
  for ($j = 0; $j < 640; $j++) {
    $color = imagecolorat($img, $j, $i);
    $data[] = ($color) & 0xff;
    $data[] = ($color >> 8) & 0xff;
    $data[] = ($color >> 16) & 0xff;
    $data[] = 0;
  }
}
$data = pack('C*', ...$data);
$pmid4 = Connection::generateId();
new CreatePixmapRequest(24, $pmid4, $wid1, 640, 480);
new PutImageRequest('ZPixmap', $pmid4, $gcid, 640, 480, 0, 0, 0, 24, $data);
new CopyAreaRequest($pmid4, $wid1, $gcid, 0, 0, 0, 0, 640, 480);


Event::loop();


new FreeGCRequest($gcid);
new FreePixmapRequest($pmid1);
new FreeCursorRequest($crid1);
new CloseFontRequest($fid1);
new DestroySubwindowsRequest($wid1);

zzz();
new DestroyWindowRequest($wid1);

zzz();
new DestroyWindowRequest($wid2);

Connection::close();
