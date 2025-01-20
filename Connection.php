<?php

namespace X11;

require_once 'Type.php';
require_once 'Atom.php';
require_once 'Request.php';
require_once 'Response.php';
require_once 'Event.php';
require_once 'Error.php';

define('DEBUG', true);

class Connection {

  protected static $socket;
  protected static $id = 0;
  public static $data;

  public static function init() {
    $displayNumber = (int)getenv('DISPLAY');
    self::connect($displayNumber);
  }

  public static function connect($displayNumber) {
    $path = "/tmp/.X11-unix/X{$displayNumber}";
    self::$socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
    socket_connect(self::$socket, $path);
    self::$data = Request::ConnectionInit(self::machineByteOrder());
  }

  public static function close() {
    socket_close(self::$socket);
  }

  public static function generateId() {
    $id = self::$data['resourceIdBase'] | self::$id;
    self::$id++;
    self::$id = self::$data['resourceIdMask'] & self::$id;
    return $id;
  }

  public static function machineByteOrder() {
    $x = 1;
    $p = pack('S', $x);
    $res = unpack('Ca/Cb', $p);
    if ($res['a'] > 0) {
      return 0x6c;
    }
    return 0x42;
  }

  public static function read($length) {
    $n = 0;
    $z = 0;
    $response = '';
    while ($n < $length) {
      $read = socket_read(self::$socket, $length - $n);
      if ($read === false) {
        throw new \Exception("Failed read.");
      }
      $r = strlen($read);
      if ($r == 0) {
        usleep(100);
        $z++;
        if ($z > 10) {
          throw new \Exception("No more data.");
        }
      } else {
        $response .= $read;
        $n += $r;
      }
    }
    return $response;
  }

  public static function write($request) {
    $length = strlen($request);
    $z = 0;
    while ($length > 0) {
      $w = socket_write(self::$socket, $request);
      if ($w === false) {
        throw new \Exception("Failed to send request.");
      }
      if ($w == 0) {
        usleep(100);
        $z++;
        if ($z > 10) {
          throw new \Exception("Failed to send request in time.");
        }
      } else {
        $request = substr($request, $w);
        $length -= $w;
      }
    }
  }

  public static function pad4($e) {
    return (4 - ($e % 4)) % 4;
  }

}

