<?php

namespace X11;

class Error {

  protected static $definition = [
    ['error', Type::BYTE],
    ['code', Type::BYTE],
    ['sequenceNumber', Type::CARD16],
    ['data', Type::CARD32],
    ['minorOpcode', Type::CARD16],
    ['majorOpcode', Type::CARD8]
  ];
  protected static $codes = [
    0, 'Request', 'Value', 'Window',
    'Pixmap', 'Atom', 'Cursor', 'Font',
    'Match', 'Drawable', 'Access', 'Alloc',
    'Colormap', 'GContext', 'IDChoice', 'Name',
    'Length', 'Implementation'
  ];
  protected static $hasData = [
    'Colormap', 'Cursor', 'Drawable', 'Font',
    'GContext', 'IDChoice', 'Pixmap', 'Window',
    'Atom', 'Value'
  ];
  protected static $errorHandler = false;

  protected static function debug($bytes, $name) {
    echo "\033[31m"; // red
    $error = self::bytesToError($bytes);
    echo "\nERROR: {$name}\n";
    foreach ($error as $name => $value) {
      if ($name == 'data' && !in_array($name, self::$hasData)) {
        continue;
      }
      echo '  ', $name, ': ', $value, "\n";
    }
    echo "\n";
    echo "\033[0m"; // reset
  }

  protected static function bytesToError($bytes) {
    $format = [];
    foreach (self::$definition as $field) {
      $format[] = Type::$format[$field[1]] . $field[0];
    }
    $format = implode('/', $format);
    $error = unpack($format, $bytes);
    return $error;
  }

  public static function setErrorHandler($callback) {
    self::$errorHandler = $callback;
  }

  public static function handle($bytes) {
    $type = unpack('C2', $bytes);
    if ($type[1] !== 0) {
      echo "Not an error\n";
      return false;
    }
    $code = $type[2];
    $name = self::$codes[$code];
    if (DEBUG) {
      self::debug($bytes, $name);
    }
    if (self::$errorHandler !== false) {
      $error = self::bytesToError($bytes);
      $error['name'] = $name;
      call_user_func(self::$errorHandler, $error);
    } else {
      throw new Exception("Bad {$name}");
    }
  }

}
