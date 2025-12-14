<?php

// this helps to use the X11 protocol
require_once dirname(dirname(__FILE__)) . '/X11.php';

// connect to the X socket
\X11\Connection::init();

// some useful variables
$screen = \X11\Connection::$data['screens'][0];
$depth = $screen['rootDepth'];
$root = $screen['root'];
$visual = $screen['rootVisual'];

// get or define WM_DELETE_WINDOW atom for receive close messages from the window manager
new \X11\InternAtomRequest(false, 'WM_DELETE_WINDOW');
$atomWmDelete = \X11\Connection::getLastResponse();
$atomWmDelete = $atomWmDelete['atom'];

// get or define WM_PROTOCOLS atom for receive close messages from the window manager
new \X11\InternAtomRequest(false, 'WM_PROTOCOLS');
$atomWmProtocols = \X11\Connection::getLastResponse();
$atomWmProtocols = $atomWmProtocols['atom'];

// generate a new id
$wid = \X11\Connection::generateId();
// create the window
$borderWidth = 0;
$width = 640;
$height = 480;
$x = 0;
$y = 0;
$blue = 0x000055;
new \X11\CreateWindowRequest(
  $depth, $wid, $root, $x,
  $y, $width, $height, $borderWidth,
  'InputOutput', $visual, ['backgroundPixel' => $blue, 'eventMask' => ['KeyPress', 'ClientMessage']]
);
// change the window title
new \X11\ChangePropertyRequest('Replace', $wid, \X11\Atom::WM_NAME, \X11\Atom::STRING, 8, 'Hello World!');
// subscribe close events
new \X11\ChangePropertyRequest('Replace', $wid, $atomWmProtocols, \X11\Atom::ATOM, 32, [$atomWmDelete]);
// show the window
new \X11\MapWindowRequest($wid);

// event handler function for proper quit
function eventHandler($event) {
  global $atomWmDelete, $atomWmProtocols;
  if ($event && $event['name'] == 'ClientMessage') {
    if ($event['type'] == $atomWmProtocols) {
      $data = unpack('L5', $event['data']);
      if ($data[1] == $atomWmDelete) {
        \X11\Event::end();
      }
    }
  }
  if ($event && $event['name'] == 'KeyPress') {
    if ($event['detail'] == 9) { // escape
      \X11\Event::end();
    }
  }
}

// use the event handler function
\X11\Event::setHandler('eventHandler');

// wait for events
\X11\Event::loop();

// clean up the mess
new \X11\DestroyWindowRequest($wid);
\X11\Connection::close();
