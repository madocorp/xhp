<?php

namespace X11;

class Connection {

  protected static $socket;
  protected static $id = 0;
  protected static $lastResponse = false;
  protected static $authProtocolName;
  protected static $authProtocolData;
  protected static $authFamilies = [
    252 => 'LocalHost',
    253 => 'Krb5Principal',
    254 => 'Netname',
    256 => 'Local',
    65535 => 'Wild'
  ];
  public static $data;
  public static $bigRequests = false;

  public static function init() {
    $displayNumber = (int)getenv('DISPLAY');
    self::connect($displayNumber);
  }

  public static function connect($displayNumber) {
    $path = "/tmp/.X11-unix/X{$displayNumber}";
    self::$socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
    $res = socket_connect(self::$socket, $path);
    if (!$res) {
      throw new \Exception("Connection failed.");
    }
    self::auth();
    new ConnectionInitRequest(self::machineByteOrder(), self::$authProtocolName, self::$authProtocolData);
    self::$data = self::$lastResponse;
    self::activateBigRequestExtension();
    self::$lastResponse = false;
  }

  public static function setTimeout($sec, $usec) {
    socket_set_option(self::$socket, SOL_SOCKET, SO_RCVTIMEO, ['sec' => $sec, 'usec' => $usec]);
  }

  protected static function auth() {
    $xauthPath = getenv('XAUTHORITY');
    if ($xauthPath === false) {
      $xauthPath = getenv('HOME') . '/.Xauthority';
    }
    $packedXauth = file_get_contents($xauthPath);
    $p = 0;
    $n = strlen($packedXauth);
    $xauth = [];
    while ($p <= $n - 1) {
      $xauth[] = self::readXauthStruct($packedXauth, $p);
    }
    self::$authProtocolName = $xauth[0]['name'];
    self::$authProtocolData = $xauth[0]['data'];
  }

  protected static function readXauthStruct($xauth, &$p) {
    $res = [];
    $unp = unpack('n', $xauth, $p);
    $res['family'] = self::$authFamilies[reset($unp)] ?? 'Unknown';
    $p += 2;
    foreach (['address', 'number', 'name', 'data'] as $field) {
      $len = unpack('n', $xauth, $p);
      $len = reset($len);
      $p += 2;
      $unp = unpack("a{$len}", $xauth, $p);
      $res[$field] = reset($unp);
      $p += $len;
    }
    return $res;
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
    $bytes = '';
    while ($n < $length) {
      $read = socket_read(self::$socket, $length - $n);
      if ($read === false) {
        throw new Timeout("TIMEOUT");
      }
      $r = strlen($read);
      if ($r == 0) {
        usleep(100);
        $z++;
        if ($z > 10) {
          throw new \Exception("No more data.");
        }
      } else {
        $bytes .= $read;
        $n += $r;
      }
    }
    return $bytes;
  }

  public static function write($bytes) {
    $length = strlen($bytes);
    $z = 0;
    while ($length > 0) {
      $w = socket_write(self::$socket, $bytes);
      if ($w === false) {
        throw new Timeout("TIMEOUT");
      }
      if ($w == 0) {
        usleep(100);
        $z++;
        if ($z > 10) {
          throw new \Exception("Failed to send request in time.");
        }
      } else {
        $bytes = substr($bytes, $w);
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

  protected static function activateBigRequestExtension() {
    new \X11\QueryExtensionRequest('BIG-REQUESTS');
    if (self::$lastResponse['majorOpcode'] > 0) {
      new \X11\EnableBigRequests(self::$lastResponse['majorOpcode']);
      self::$bigRequests = self::$lastResponse['maximumRequestLength'];
    } else {
      trigger_error("No BIG-REQUEST extension!", E_USER_WARNING);
    }
  }

}
