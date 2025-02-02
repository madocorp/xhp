<?php

namespace X11;

class ArrayToBytes {

  protected $template;
  protected $values;
  protected $bytes = '';
  protected $lengths = [];
  protected $lengths_4 = [];
  protected $lengths_n = [];

  public function __construct($template, $values = []) {
    $this->template = $template;
    $this->values = $values;
    $this->parse();
  }

  public function get() {
    return $this->bytes;
  }

  public function getTemplate() {
    return $this->template;
  }

  public function getValues() {
    return $this->values;
  }

  protected function parse() {
    $this->explainFooLists();
    $this->explainValueLists();
    $this->calculateLengths();
    foreach ($this->template as $field) {
      $name = $field[0];
      $type = $field[1];
      switch ($type) {
        case Type::ENUM8:
        case Type::ENUM16:
        case Type::ENUM32:
          $enum = $field[2];
          $this->enumToByte($name, $type, $enum);
          break;
        case Type::BITMASK8:
        case Type::BITMASK16:
        case Type::BITMASK32:
          $bits = $field[2];
          $this->bitmaskToByte($name, $type, $bits);
          break;
        case Type::STR:
          $this->strToByte($name);
          break;
        case Type::STRING8:
          $this->stringToByte($name, $field[2] ?? true);
          break;
        case Type::UNUSED:
          $length = $field[2];
          $this->UnusedToByte($length);
          break;
        default:
          $this->simpleToByte($name, $type);
          break;
      }
    }
  }

  protected function explainFooLists() {
    $template = [];
    $values = [];
    foreach ($this->template as $field) {
      $type = $field[1];
      $name = $field[0];
      if ($type != Type::FLIST) {
        $template[] = $field;
        if (isset($this->values[$name])) {
          $values[$name] = $this->values[$name];
        }
        continue;
      }
      $fooMap = $field[2];
      $n = count($this->values[$name]);
      $this->lengths_n[$name] = $n;
      for ($i = 0; $i < $n; $i++) {
        foreach ($fooMap as $f => $fooItem) {
          $fullName = "{$name}_{$i}.{$fooItem[0]}";
          $fooItem[0] = $fullName;
          $template[] = $fooItem;
          if (is_array($this->values[$name][$i])) {
            $fooValues = array_values($this->values[$name][$i]);
            $values[$fullName] = $fooValues[$f];
          } else {
            $values[$fullName] = $this->values[$name][$i];
          }
        }
      }
    }
    $this->template = $template;
    $this->values = $values;
  }

  protected function explainValueLists() {
    $template = [];
    $values = [];
    foreach ($this->template as $field) {
      $type = $field[1];
      $name = $field[0];
      if ($type != Type::VLIST) {
        $template[] = $field;
        if (isset($this->values[$name])) {
          $values[$name] = $this->values[$name];
        }
        continue;
      }
      $template[] = ["valueMask_{$name}", Type::CARD32];
      $values["valueMask_{$name}"] = 0;
      $valueMask = 0;
      $listValues = $this->values[$name];
      $valueMap = $field[2];
      foreach ($valueMap as $i => $valueItem) {
        $valueName = $valueItem[0];
        $valueType = $valueItem[1];
        if (isset($listValues[$valueName])) {
          $valueMask |= pow(2, $i);
          $template[] = $valueItem;
          $values[$valueName] = $listValues[$valueName];
          $size = Type::$size[$valueType];
          if ($size < 4) {
            $template[] = ['unused', Type::UNUSED, 4 - $size];
          }
        }
      }
      $values["valueMask_{$name}"] = $valueMask;
    }
    $this->template = $template;
    $this->values = $values;
  }

  protected function calculateLengths() {
    $lengths = [];
    $requestLength = 0;
    foreach ($this->template as $field) {
      $name = $field[0];
      $type = $field[1];
      if ($type == Type::STR) {
        $string = $this->values[$name];
        $n = strlen($string);
        $requestLength += $n + 1;
      } else if ($type == Type::STRING8) {
        $string = $this->values[$name];
        $n = strlen($string);
        $pad = $field[2] ?? true;
        if ($pad) {
          $p = Connection::pad4($n);
        } else {
          $p = 0;
        }
        $this->lengths[$name] = $n + $p;
        $this->lengths_4[$name] = (($n + $p) >> 2);
        $requestLength += $n + $p;
      } else if ($type == Type::UNUSED) {
        $n = $field[2];
        $requestLength += $n;
      } else {
        $size = Type::$size[$type];
        $requestLength += $size;
      }
    }
    foreach ($this->template as $field) {
      $name = $field[0];
      $type = $field[1];
      if (in_array($type, [Type::LENGTH8, Type::LENGTH16, Type::LENGTH32])) {
        $sizeOf = $field[2];
        $this->values[$name] = $this->lengths[$sizeOf];
      }
      if (in_array($type, [Type::LENGTH8_4, Type::LENGTH16_4, Type::LENGTH32_4])) {
        $sizeOf = $field[2];
        $this->values[$name] = $this->lengths_4[$sizeOf];
      }
      if (in_array($type, [Type::LENGTH8_N, Type::LENGTH16_N, Type::LENGTH32_N])) {
        $sizeOf = $field[2];
        $this->values[$name] = $this->lengths_n[$sizeOf];
      }
    }
    $this->values['requestLength'] = ($requestLength >> 2);
  }

  protected function simpleToByte($name, $type) {
    $value = $this->values[$name];
    $format = Type::$format[$type];
    $this->bytes .= pack($format, $value);
  }

  protected function enumToByte($name, $type, $enum) {
    $value = $this->values[$name];
    $value = array_search($value, $enum);
    $format = Type::$format[$type];
    $this->bytes .= pack($format, (int)$value);
  }

  protected function bitmaskToByte($name, $type, $bits) {
    $value = $this->values[$name];
    $mask = 0;
    foreach ($bits as $i => $bit) {
      if (in_array($bit, $value)) {
        $mask |= pow(2, $i);
      }
    }
    $format = Type::$format[$type];
    $this->bytes .= pack($format, $mask);
  }

  protected function unusedToByte($length) {
    if ($length <= 0) {
      return;
    }
    $format = str_repeat('C', $length);
    $zeros = array_fill(0, $length, 0);
    $this->bytes .= pack($format, ...$zeros);
  }

  protected function stringToByte($name, $pad) {
    $string = $this->values[$name];
    $n = strlen($string);
    $format = Type::$format[Type::STRING8];
    $this->bytes .= pack($format . $n, $string);
    if ($pad) {
      $p = Connection::pad4($n);
      if ($p > 0) {
        $this->unusedToByte($p);
      }
    }
  }

  protected function strToByte($name) {
    $string = $this->values[$name];
    $n = strlen($string);
    $format = Type::$format[Type::STRING8];
    $lengthFormat = Type::$format[Type::CARD8];
    $this->bytes .= pack($lengthFormat . $format . $n, $n, $string);
  }

}
