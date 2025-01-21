<?php

namespace X11;

class Request {

  protected function debugRequest($data, $request) {
    $requestName = basename(str_replace('\\', '/', get_class($this)));
    echo "\n", str_pad("[ Request: {$requestName} ]", 120, '-', STR_PAD_BOTH), "\n";
    foreach ($data as $field) {
      if ($field[2] == Type::STRING8) {
        echo str_pad('"' . $field[1] . '"', 40, ' ', STR_PAD_LEFT);
        echo str_pad('[' . strlen($field[1]) . ']', 26, ' ', STR_PAD_LEFT);
      } else if ($field[2] == Type::PAD4) {
        echo str_pad('[' . $field[1] . ']', 66, ' ', STR_PAD_LEFT);
      } else {
        echo str_pad($field[1], 40, ' ', STR_PAD_LEFT);
        if (is_int($field[1])) {
          echo str_pad('0x' . dechex($field[1]), 16, ' ', STR_PAD_LEFT);
        } else {
          echo str_pad('', 16);
        }
        echo str_pad(' [' . Type::$size[Type::$format[$field[2]]] . ']', 10, ' ', STR_PAD_LEFT);
      }
      echo " {$field[0]}\n";
    }
    $bytes = unpack('C*', $request);
    echo '| ';
    foreach ($bytes as $i => $byte) {
      $hex = dechex($byte);
      echo '0x', ($byte < 0x10 ? '0' : ''), $hex, ' ';
      if ($i % 4 == 0) {
        echo '| ';
      }
      if ($i % 16 == 0) {
        echo "\n| ";
      }
    }
    echo "\n";
  }

  protected function doRequest($data) {
    $formatString = '';
    $values = [];
    foreach ($data as $field) {
      $value = $field[1];
      $type = $field[2];
      if ($type == Type::PAD4) {
        if ($value > 0) {
          $formatString .= str_repeat('C', $value);
          $values = array_merge($values, array_fill(0, $value, 0));
        }
      } else {
        if (in_array($type, [Type::ENUM8, Type::ENUM16, Type::ENUM32])) {
          $enum = $field[3];
          $value = array_search($value, $enum);
        }
        $format = Type::$format[$type];
        $formatString .= $format;
        $values[] = $value;
      }
    }
    $request = pack($formatString, ...$values);
    if (X11_DEBUG) {
      $this->debugRequest($data, $request);
    }
    Connection::write($request);
  }

  protected function addBitmaskList($data, $valueMap, $values) {
    $valueMask = 0;
    $valueList = [];
    $length = 0;
    foreach ($valueMap as $i => $map) {
      $name = $map[0];
      if (!isset($values[$name])) {
        continue;
      }
      $type = $map[1];
      $value = $values[$name];
      if (in_array($type, [Type::ENUM8, Type::ENUM16, Type::ENUM32])) {
        $possibleValues = $map[2];
        $valueList[] = [$name, $value, $type, $possibleValues];
      } else {
        $valueList[] = [$name, $value, $type];
      }
      $fieldLength = Type::$size[Type::$format[$type]];
      if ($fieldLength < 4) {
        $valueList[] = ['pad', 4 - $fieldLength, Type::PAD4];
      }
      $length += 4;
      $valueMask |= pow(2, $i);
    }
    $data[2][1] += $length >> 2;
    $data[] = ['valueMask', $valueMask, Type::CARD32];
    return array_merge($data, $valueList);
  }

  protected function debugResponse($response) {
    foreach ($response as $name => $value) {
      echo '▶  ', $name, ': ', $value, "\n";
    }
    echo "\n";
  }

  protected function receiveResponse($specification) {
    $length = 0;
    $formatString = [];
    $enums = [];
    $bools = [];
    foreach ($specification as $field) {
      $name = $field[0];
      $type = $field[1];
      $format = Type::$format[$type];
      if ($type == Type::STRING8) {
        $stringLength = $field[2];
        $format = str_replace('*', $stringLength, $format);
        $formatString[] = "{$format}{$name}";
        $length += $stringLength;
        $pad = Connection::pad4($stringLength);
        if ($pad > 0) {
          $formatString[] = "x{$pad}";
          $length += $pad;
        }
      } else {
        $formatString[] = "{$format}{$name}";
        $length += Type::$size[$format];
      }
      if (in_array($type, [Type::ENUM8, Type::ENUM16, Type::ENUM32])) {
        $enums[$name] = $field[2];
      }
      if ($type == Type::BOOL) {
        $bools[] = $name;
      }
    }
    $formatString = implode('/', $formatString);
    $response = Connection::read($length);
    $response = unpack($formatString, $response);
    foreach ($enums as $name => $values) {
      $response[$name] = $values[$response[$name]];
    }
    foreach ($bools as $name) {
      $response[$name] = ($response[$name] > 0);
    }
    if (count($response) == 1) {
      return reset($response);
    }
    unset($response['unused']);
    if (X11_DEBUG) {
      $this->debugResponse($response);
    }
    return $response;
  }

}
