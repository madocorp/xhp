<?php

namespace X11;

class Request {

  protected function debugRequest($data, $request) {
    $maxDebugRows = 15;
    $requestName = basename(str_replace('\\', '/', get_class($this)));
    echo "\033[32m"; // green
    echo "\n", str_pad("[ Request: {$requestName} ]", 120, '-', STR_PAD_BOTH), "\n";
    foreach ($data as $n => $field) {
      if ($field[2] == Type::STRING8) {
        echo str_pad('"' . $field[1] . '"', 40, ' ', STR_PAD_LEFT);
        echo str_pad('[' . strlen($field[1]) . ']', 26, ' ', STR_PAD_LEFT);
      } else if ($field[2] == Type::PAD4) {
        echo str_pad('[' . $field[1] . ']', 66, ' ', STR_PAD_LEFT);
      } else if (in_array($field[2], [Type::BITMASK8, Type::BITMASK16, Type::BITMASK32])) {
        echo str_pad(implode(',', $field[1]), 40, ' ', STR_PAD_LEFT);
        echo str_pad(' [' . Type::$size[Type::$format[$field[2]]] . ']', 26, ' ', STR_PAD_LEFT);
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
      if ($n >= $maxDebugRows) {
        echo "... (+" . count($data) - $n - 1 . " rows)\n";
        break;
      }
    }
    Connection::byteDebug($request);
    echo "\033[0m"; // reset
  }

  protected function preprocessRequestData($data) {
    $data2 = [];
    $length = 0;
    foreach ($data as $field) {
      if ($field[2] == Type::VLIST) {
        $maskpos = count($data2);
        $data2[] = ['valueMask', 0, Type::CARD32];
        $values = $field[1];
        $valueMap = $field[3];
        $valueMask = 0;
        foreach ($valueMap as $i => $map) {
          $name = $map[0];
          if (!isset($values[$name])) {
            continue;
          }
          $valueMask |= pow(2, $i);
          $map2 = [];
          foreach ($map as $i => $item) {
            $map2[] = $item;
            if ($i == 0) {
              $map2[] = $values[$name];
            }
          }
          $map = $map2;
//          array_splice($map, 1, 0, $values[$name]);
          $data2[] = $map;
          $fieldLength = Type::$size[Type::$format[$map[2]]];
          if ($fieldLength < 4) {
            $data2[] = ['pad', 4 - $fieldLength, Type::PAD4];
          }
        }
        $length += count($values);
        $data2[$maskpos][1] = $valueMask;
      } else if ($field[2] == Type::FLIST) {
        $foos = $field[1];
        $fooMap = $field[3];
        $fooLength = 0;
        foreach ($fooMap as $fooField) {
          $fooLength += Type::$size[Type::$format[$fooField[1]]];
        }
        foreach ($foos as $foo) {
          foreach ($fooMap as $fooField) {
            $fooFieldWithValue = $fooField;
//            array_splice($fooFieldWithValue, 1, 0, $foo[$fooField[0]]);
            $fooFieldWithValue = [];
            foreach ($fooField as $i => $item) {
              $fooFieldWithValue[] = $item;
              if ($i == 0) {
                $fooFieldWithValue[] = $foo[$fooField[0]];
              }
            }
            $data2[] = $fooFieldWithValue;
          }
        }
        $length += (count($foos) * $fooLength) >> 2;
      } else if ($field[2] == Type::STRING8) {
        $data2[] = $field;
        $n = strlen($field[1]);
        $p = Connection::pad4($n);
        if ($p > 0) {
          $data2[] = ['pad', $p, Type::PAD4];
        }
        $length += (($n + $p) >> 2);
      } else {
        $data2[] = $field;
      }
    }

    $data2[2][1] += $length;
    return $data2;
  }

  protected function sendRequest($data) {
    $data = $this->preprocessRequestData($data);
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
        if (in_array($type, [Type::BITMASK8, Type::BITMASK16, Type::BITMASK32])) {
          $bits = $field[1];
          $valueList = $field[3];
          $valueMask = 0;
          foreach ($valueList as $i => $value) {
            if (in_array($value, $bits)) {
              $valueMask |= pow(2, $i);
            }
          }
          $value = $valueMask;
          if ($field[2] == Type::BITMASK8) {
            $type = Type::CARD8;
          } else if ($field[2] == Type::BITMASK16) {
            $type = Type::CARD16;
          } else {
            $type = Type::CARD32;
          }
        } else if (in_array($type, [Type::ENUM8, Type::ENUM16, Type::ENUM32])) {
          $enum = $field[3];
          $value = array_search($value, $enum);
        }
        $format = Type::$format[$type];
        $formatString .= $format;
        $values[] = $value;
      }
    }
    $request = pack($formatString, ...$values);
    if (DEBUG) {
      $this->debugRequest($data, $request);
    }
    Connection::write($request);
  }

  protected function debugResponse($response) {
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

  protected function waitForResponse() {
    while (true) {
      $bytes = Connection::read(32);
      $header = unpack('Ctype/x5/Slength', $bytes);
      if ($header['type'] == 0) {
        Error::handle($bytes);
      } else  if ($header['type'] > 1) {
        Event::handle($bytes);
      } else {
        break;
      }
    }
    return $bytes;
  }

  protected function receiveResponse($specification, $start = true) {
    if ($start) {
      $bytes = self::waitForResponse();
      $length = -32;
    } else {
      $bytes = '';
      $length = 0;
    }
    $formatString = [];
    $enums = [];
    $bools = [];
    foreach ($specification as $field) {
      $name = $field[0];
      $type = $field[1];
      if ($type == Type::STRING8) {
        $format = Type::$format[$type];
        $stringLength = $field[2];
        $pad = true;
        if (isset($field[3]) && $field[3] == false) {
          $pad = false;
        }
        $format = str_replace('*', $stringLength, $format);
        $formatString[] = "{$format}{$name}";
        $length += $stringLength;
        if ($pad) {
          $pad = Connection::pad4($stringLength);
          if ($pad > 0) {
            $formatString[] = "x{$pad}";
            $length += $pad;
          }
        }
      } else if ($type == Type::PAD4) {
        $pad = $field[2];
        $formatString[] = "x{$pad}";
        $length += $pad;
      } else {
        $format = Type::$format[$type];
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
    if ($length > 0) {
      $bytes .= Connection::read($length);
    }
    $response = unpack($formatString, $bytes);
    foreach ($enums as $name => $values) {
      $response[$name] = $values[$response[$name]];
    }
    foreach ($bools as $name) {
      $response[$name] = ($response[$name] > 0);
    }
    unset($response['unused']);
    if (DEBUG) {
      $this->debugResponse($response);
    }
    if (count($response) == 1) {
      return reset($response);
    }
    return $response;
  }

}
