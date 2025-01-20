<?php

require_once 'Connection.php';

use X11\Connection;
use X11\Request;
use X11\Event;
use X11\Atom;

function eventHandler($event) {
  if ($event['name'] == 'KeyPress') {
    if ($event['detail'] == 9) { // escape
      Event::end();
    }
  }
}

Connection::init();
$screen = Connection::$data['screens'][0];

$depth = $screen['rootDepth'];
$wid = Connection::generateId();
$parent = $screen['root'];
$x = 0;
$y = 0;
$width = 640;
$height = 480;
$borderWidth = 0;
$class = 'InputOutput';
$visual = $screen['rootVisual'];
$values = ['backgroundPixel' => 0x000055, 'eventMask' => 0x8003];
Request::CreateWindow(
  $depth, $wid, $parent, $x,
  $y, $width, $height, $borderWidth,
  $class, $visual, $values
);
Request::ChangeProperty('Replace', $wid, Atom::WM_NAME, Atom::STRING, 8, 'Hello World!');
Request::GetWindowAttributes($wid);
Request::MapWindow($wid);

$wid2 = Connection::generateId();
Request::CreateWindow(
  $depth, $wid2, $parent, $x,
  $y, 200, 200, $borderWidth,
  $class, $visual, ['backgroundPixel' => 0x550000, 'eventMask' => 0x8003]
);
Request::ChangeProperty('Replace', $wid2, Atom::WM_NAME, Atom::STRING, 8, 'Hello World2!');
Request::MapWindow($wid2);

$swid1 = Connection::generateId();
Request::CreateWindow(
  $depth, $swid1, $wid, 10,
  10, 100, 100, 0,
  $class, $visual, ['backgroundPixel' => 0xaa0000]
);

$swid2 = Connection::generateId();
Request::CreateWindow(
  $depth, $swid2, $wid, 30,
  30, 100, 100, 0,
  $class, $visual, ['backgroundPixel' => 0xaaaa00]
);

$swid3 = Connection::generateId();
Request::CreateWindow(
  $depth, $swid3, $wid, 50,
  50, 100, 100, 5,
  $class, $visual, ['backgroundPixel' => 0x00aa00]
);
Request::MapSubwindows($wid);

Request::ChangeWindowAttributes($swid3, ['borderPixel' => 0xffffff]);

Request::CirculateWindow('RaiseLowest', $wid);

Request::ChangeSaveSet('Delete', $parent); // ???

Request::ReparentWindow($swid2, $wid2, 50, 50);


Event::loop('eventHandler');

Request::DestroySubwindows($wid);
sleep(1);
Request::DestroyWindow($wid);
sleep(1);
Request::DestroyWindow($wid2);
