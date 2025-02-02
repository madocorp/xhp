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

  protected static function bytesToError($bytes) {
    $format = [];
    foreach (self::$definition as $field) {
      $name = $field[0];
      $type = $field[1];
      $format[] = Type::$format[$type] . $name;
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
      Debug::error(self::bytesToError($bytes), $name, in_array($name, self::$hasData));
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
