<?php

require_once 'Type.php';
require_once 'Atom.php';
require_once 'Connection.php';
require_once 'Request.php';
require_once 'Event.php';
require_once 'Error.php';

if (!defined('X11_DEBUG')) {
  define('X11_DEBUG', false);
}

spl_autoload_register(function ($class) {
  $class = explode('\\', $class);
  if ($class[0] == 'X11') {
    require_once "Request/{$class[1]}.php";
  }
});


