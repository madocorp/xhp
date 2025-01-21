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
  usleep(500000);
}

\X11\Connection::init();
$screen = \X11\Connection::$data['screens'][0];

$depth = $screen['rootDepth'];
$wid = \X11\Connection::generateId();
$parent = $screen['root'];
$x = 0;
$y = 0;
$width = 640;
$height = 480;
$borderWidth = 0;
$class = 'InputOutput';
$visual = $screen['rootVisual'];
$values = ['backgroundPixel' => 0x000055, 'eventMask' => 0x8003];

new \X11\CreateWindowRequest(
  $depth, $wid, $parent, $x,
  $y, $width, $height, $borderWidth,
  $class, $visual, $values
);
new \X11\ChangePropertyRequest('Replace', $wid, Atom::WM_NAME, Atom::STRING, 8, 'Hello World!');
new \X11\GetWindowAttributesRequest($wid);
new \X11\MapWindowRequest($wid);

zzz();
$wid2 = Connection::generateId();
new \X11\CreateWindowRequest(
  $depth, $wid2, $parent, $x,
  $y, 200, 200, $borderWidth,
  $class, $visual, ['backgroundPixel' => 0x550000, 'eventMask' => 0x8003]
);
new \X11\ChangePropertyRequest('Replace', $wid2, Atom::WM_NAME, Atom::STRING, 8, 'Hello World2!');
new \X11\MapWindowRequest($wid2);

zzz();
$swid1 = Connection::generateId();
new \X11\CreateWindowRequest(
  $depth, $swid1, $wid, 10,
  10, 100, 100, 0,
  $class, $visual, ['backgroundPixel' => 0xaa0000]
);
$swid2 = Connection::generateId();
new \X11\CreateWindowRequest(
  $depth, $swid2, $wid, 30,
  30, 100, 100, 0,
  $class, $visual, ['backgroundPixel' => 0xaaaa00]
);
$swid3 = Connection::generateId();
new \X11\CreateWindowRequest(
  $depth, $swid3, $wid, 50,
  50, 100, 100, 5,
  $class, $visual, ['backgroundPixel' => 0x00aa00]
);
new \X11\MapSubwindowsRequest($wid);

zzz();
new \X11\ChangeWindowAttributesRequest($swid3, ['borderPixel' => 0xffffff]);

zzz();
new \X11\CirculateWindowRequest('RaiseLowest', $wid);

zzz();
new \X11\ChangeSaveSetRequest('Delete', $parent); // ???

zzz();
new \X11\ReparentWindowRequest($swid2, $wid2, 50, 50);

Event::loop('\X11\eventHandler');

new \X11\DestroySubwindowsRequest($wid);

zzz();
new \X11\DestroyWindowRequest($wid);

zzz();
new \X11\DestroyWindowRequest($wid2);
