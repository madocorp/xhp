<?php

namespace X11;

define('X11_DEBUG', true);
require_once 'X11.php';

function eventHandler($event) {
  if ($event['name'] == 'KeyPress') {
    if ($event['detail'] == 9) { // escape
      Event::end();
    }
  }
}

function zzz() {
  usleep(50000);
}

Event::setHandler('\X11\eventHandler');

\X11\Connection::init();
$screen = \X11\Connection::$data['screens'][0];

$depth = $screen['rootDepth'];
$wid1 = \X11\Connection::generateId();
$root = $screen['root'];
$borderWidth = 0;
$class = 'InputOutput';
$visual = $screen['rootVisual'];
$values = ['backgroundPixel' => 0x000055, 'eventMask' => 0x8003];
new \X11\CreateWindowRequest(
  $depth, $wid1, $root, 0,
  0, 640, 480, $borderWidth,
  $class, $visual, $values
);
new \X11\ChangePropertyRequest('Replace', $wid1, Atom::WM_NAME, Atom::STRING, 8, 'Hello World!');
new \X11\MapWindowRequest($wid1);
new \X11\GetWindowAttributesRequest($wid1);

zzz();
$wid2 = Connection::generateId();
new \X11\CreateWindowRequest(
  $depth, $wid2, $root, 0,
  0, 200, 200, $borderWidth,
  $class, $visual, ['backgroundPixel' => 0x550000, 'eventMask' => 0x8003]
);
new \X11\ChangePropertyRequest('Replace', $wid2, Atom::WM_NAME, Atom::STRING, 8, 'Hello World2!');
new \X11\MapWindowRequest($wid2);

zzz();
$swid1 = Connection::generateId();
new \X11\CreateWindowRequest(
  $depth, $swid1, $wid1, 10,
  10, 100, 100, 0,
  $class, $visual, ['backgroundPixel' => 0xaa0000]
);
$swid2 = Connection::generateId();
new \X11\CreateWindowRequest(
  $depth, $swid2, $wid1, 30,
  30, 100, 100, 0,
  $class, $visual, ['backgroundPixel' => 0xaaaa00]
);
$swid3 = Connection::generateId();
new \X11\CreateWindowRequest(
  $depth, $swid3, $wid1, 50,
  50, 100, 100, 5,
  $class, $visual, ['backgroundPixel' => 0x00aa00]
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
new \X11\PolyRectangleRequest($wid1, $gcid, [['x' => 500, 'y' => 400, 'width' => 100, 'height' => 50]]);

zzz();
new \X11\PolySegmentRequest($wid1, $gcid, [['x1' => 500, 'y1' => 400, 'x2' => 550, 'y2' => 450]]);

zzz();
new \X11\PolyArcRequest($wid1, $gcid, [['x' => 500, 'y' => 400, 'width' => 100, 'height' => 50, 'angle1' => 0, 'angle2' => 180 * 64]]);

zzz();
new \X11\PolyFillRectangleRequest($wid1, $gcid, [['x' => 200, 'y' => 400, 'width' => 100, 'height' => 50]]);

zzz();
new \X11\PolyFillArcRequest($wid1, $gcid, [['x' => 500, 'y' => 100, 'width' => 100, 'height' => 50, 'angle1' => 0, 'angle2' => 180 * 64]]);

zzz();
new \X11\FillPolyRequest($wid1, $gcid, 'Convex', 'Origin', [['x' => 100, 'y' => 0], ['x' => 200, 'y' => 0], ['x' => 150, 'y' => 50]]);

zzz();
new \X11\ClearAreaRequest(false, $wid1, 200, 200, 200, 200);

zzz();
$pmid1 = Connection::generateId();
new \X11\CreatePixmapRequest(1, $pmid1, $wid1, 32, 32);
$pmid2 = Connection::generateId();
new \X11\CreatePixmapRequest(1, $pmid2, $wid1, 32, 32);
$gcid2 = Connection::generateId();
new \X11\CreateGCRequest($gcid2, $pmid1, []);
new \X11\ChangeGCRequest($gcid2, ['foreground' => 1, 'background' => 0]);
new \X11\PolyFillRectangleRequest($pmid2, $gcid2, [['x' => 0, 'y' => 0, 'width' => 32, 'height' => 32]]);
new \X11\PolyFillRectangleRequest($pmid1, $gcid2, [['x' => 0, 'y' => 0, 'width' => 32, 'height' => 32]]);
new \X11\ChangeGCRequest($gcid2, ['foreground' => 0, 'background' => 1]);
new \X11\PolyFillRectangleRequest($pmid1, $gcid2, [['x' => 0, 'y' => 0, 'width' => 16, 'height' => 16]]);
new \X11\PolyFillRectangleRequest($pmid2, $gcid2, [['x' => 8, 'y' => 8, 'width' => 16, 'height' => 16]]);
new \X11\ChangeGCRequest($gcid2, ['foreground' => 1, 'background' => 0]);
$crid1 = Connection::generateId();
new \X11\CreateCursorRequest($crid1, $pmid1, $pmid2, 0xffff, 0xffff, 0x0, 0xffff, 0x0, 0x0, 16, 16);
new \X11\ChangeWindowAttributesRequest($wid1, ['cursor' => $crid1]);

zzz();
$pmid3 = Connection::generateId();
new \X11\CreatePixmapRequest(24, $pmid3, $root, 100, 100);
new \X11\PutImageRequest('ZPixmap', $pmid3, $gcid, 100, 100, 0, 0, 0, 24, pack("C*", ...array_fill(0, 4*100*100, 0xff)));
new \X11\PolyArcRequest($pmid3, $gcid, [['x' => 10, 'y' => 10, 'width' => 80, 'height' => 80, 'angle1' => 0, 'angle2' => 360 * 64]]);
new \X11\CopyAreaRequest($pmid3, $wid1, $gcid, 0, 0, 300, 300, 100, 100);

zzz();
new \X11\ListFontsRequest(1000, '*fixed*');

$fid1 = Connection::generateId();
new \X11\OpenFontRequest($fid1, '-misc-fixed-medium-r-semicondensed--0-0-75-75-c-0-iso8859-2');
$fid2 = Connection::generateId();
new \X11\OpenFontRequest($fid2, '-misc-fixed-medium-r-normal-ko-0-0-100-100-c-0-iso10646-1');
new \X11\ChangeGCRequest($gcid, ['font' => $fid1, 'background' => 0x550000]);
new \X11\ImageText8Request($wid2, $gcid, 10, 10, mb_convert_encoding('Hello Wőrld!','ISO8859-2','UTF-8'));
new \X11\ChangeGCRequest($gcid, ['font' => $fid2, 'background' => 0x550000]);
new \X11\ImageText16Request($wid2, $gcid, 10, 30, 'Hélló Wőrld!');


zzz();
new \X11\NoOperationRequest(16);

zzz();
new \X11\BellRequest(50);

Event::loop();

new \X11\DestroySubwindowsRequest($wid1);

zzz();
new \X11\DestroyWindowRequest($wid1);

zzz();
new \X11\DestroyWindowRequest($wid2);
