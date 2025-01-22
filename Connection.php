<?php

namespace X11;

class Connection {

  protected static $socket;
  protected static $id = 0;
  protected static $lastResponse = false;
  public static $data;

  public static function init() {
    $displayNumber = (int)getenv('DISPLAY');
    self::connect($displayNumber);
  }

  public static function connect($displayNumber) {
    $path = "/tmp/.X11-unix/X{$displayNumber}";
    self::$socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
    socket_connect(self::$socket, $path);
    new ConnectionInitRequest(self::machineByteOrder());
    self::$data = self::$lastResponse;
    self::$lastResponse = false;
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

  public static function setResponse($response) {
    self::$lastResponse = $response;
  }

  public static function getLastResponse() {
    return self::$lastResponse;
  }

  public static function byteDebug($bytes) {
    $cut = '';
    if (strlen($bytes) > 128) {
      $cut = "\n... (+" . strlen($bytes) - 128 . " bytes)";
      $bytes = substr($bytes, 0, 128);
    }
    echo "\033[33m"; // yellow
    $bytes = unpack('C*', $bytes);
    $n = count($bytes);
    echo '| ';
    foreach ($bytes as $i => $byte) {
      $hex = dechex($byte);
      echo '0x', ($byte < 0x10 ? '0' : ''), $hex, ' ';
      if ($i % 4 == 0) {
        echo '| ';
      }
      if ($i % 16 == 0 && $i < $n) {
        echo "\n| ";
      }
    }
    echo "{$cut}\n";
    echo "\033[0m"; // reset
  }

}



