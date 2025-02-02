<?php

namespace X11;

class Debug {

  public static function bytes($bytes) {
    $cut = '';
    if (strlen($bytes) > 128) {
      $cut = "\n... (+" . strlen($bytes) - 128 . " bytes)";
      $bytes = substr($bytes, 0, 128);
    }
    echo "\033[33m"; // brown
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

  public static function request($template, $values, $bytes, $requestName) {
    $maxDebugRows = 15;
    echo "\033[32m"; // green
    echo "\n", str_pad("[ Request: {$requestName} ]", 120, '-', STR_PAD_BOTH), "\n";
    foreach ($template as $i => $field) {
      if ($i > 15) {
        break;
      }
      $name = $field[0];
      $type = $field[1];
      if (isset($values[$name])) {
        $value = $values[$name];
      } else {
        $value = false;
      }
      switch ($type) {
        case Type::UNUSED:
          $value = '-';
          $size = $template[$i][2];
          $hexvalue = '0x00';
          break;
        case Type::STR:
          $size = strlen($value) + 1;
          $hexvalue = '-';
          $value = '"' . $value . '"';
          break;
        case Type::STRING8:
          $hexvalue = '-';
          if ($value !== false) {
            $size = strlen($value);
            $value = '"' . $value . '"';
            if (strlen($value) > 58) {
              $value = substr($value, 0, 54) . '..."';
            }
          } else {
            $value = '-';
            $size = 0;
          }
          break;
        case Type::ENUM8:
        case Type::ENUM16:
        case Type::ENUM32:
          $size = Type::$size[$type];
          $fsize = 2 * $size;
          $hexvalue = sprintf("0x%0{$fsize}x", array_search($value, $field[2]));
          break;
        case Type::BITMASK8:
        case Type::BITMASK16:
        case Type::BITMASK32:
          $size = Type::$size[$type];
          $fsize = 2 * $size;
          $mask = 0;
          foreach ($field[2] as $i => $bit) {
            if (in_array($bit, $value)) {
              $mask |= pow(2, $i);
            }
          }
          $value = implode(', ', $value);
          $hexvalue = sprintf("0x%0{$fsize}x", $mask);
          break;
        default:
          $size = Type::$size[$type];
          if ($value !== false) {
            $fsize = 2 * $size;
            $hexvalue = sprintf("0x%0{$fsize}x", $value);
          } else {
            $value = '-';
            $hexvalue = '0x00';
          }
          break;
      }
      echo '  ', str_pad(substr($value, 0, 58), 60, ' ', STR_PAD_LEFT);
      echo '  ', str_pad($hexvalue, 10, ' ', STR_PAD_LEFT);
      echo '  ', str_pad($name, 34, ' ', STR_PAD_LEFT);
      echo '  ', str_pad("[{$size}]", 8, ' ', STR_PAD_LEFT);
      echo "\n";
    }
    Debug::bytes($bytes);
    echo "\033[0m"; // reset
  }

  public static function response($response) {
    foreach ($response as $name => $value) {
      if (is_bool($value)) {
        $value = $value ? 'true' : 'false';
      } else if (is_int($value)) {
        $value = sprintf("%d  (0x%x)", $value, $value);
      }
      echo '▶  ', $name, ': ', $value, "\n";
    }
    echo "\n";
  }

  public static function event($event, $name) {
    echo "\033[36m"; // cyan
    echo "\n", str_pad("[ {$name} ]", 120, '-', STR_PAD_BOTH), "\n";
    foreach ($event as $name => $value) {
      echo '*  ', $name, ': ', $value, "\n";
    }
    echo "\n";
    echo "\033[0m"; // reset
  }

  public static function error($error, $name, $hasData) {
    echo "\033[31m"; // red
    echo "\nERROR: {$name}\n";
    foreach ($error as $name => $value) {
      if ($name == 'data' && !$hasData) {
        continue;
      }
      echo '  ', $name, ': ', $value, "\n";
    }
    echo "\n";
    echo "\033[0m"; // reset
  }

}
