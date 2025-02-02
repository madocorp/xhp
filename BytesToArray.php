<?php

namespace X11;

class BytesToArray {

  protected $template;
  protected $bytes = '';
  protected $values = [];
  protected $lengths = [];
  protected $lengths_4 = [];
  protected $lengths_n = [];

  public function __construct($template) {
    $this->template = $template;
  }

  public function get() {
    return $this->values;
  }

  public function getTemplate() {
    return $this->template;
  }

  public function getLength() {
    $length = 0;
    foreach ($this->template as $field) {
      $type = $field[1];
      switch ($type) {
        case Type::UNUSED:
          $length += $field[2];
          break;
        case Type::STRING8:
          $length += $field[2];
          if (isset($field[3]) && $field[3] == false) {
            $pad = 0;
          } else {
            $pad = Connection::pad4($length);
          }
          $length += $pad;
          break;
        default:
          $length += Type::$size[$type];
          break;
      }
    }
    return $length;
  }

  public function parse($bytes) {
    $this->bytes = $bytes;
    $format = [];
    foreach ($this->template as $field) {
      $name = $field[0];
      $type = $field[1];
      switch ($type) {
        case Type::STRING8:
          $length = $field[2];
          $format[] = Type::$format[$type] . $length . $name;
          if (isset($field[3]) && $field[3] == false) {
            $pad = 0;
          } else {
            $pad = Connection::pad4($length);
          }
          if ($pad > 0) {
            $format[] = Type::$format[Type::UNUSED] . $pad . 'unused';
          }
          break;
        case Type::UNUSED:
          $size = $field[2];
          $format[] = Type::$format[$type] . $size . 'unused';
          break;
        default:
          $format[] = Type::$format[$type] . $name;
          break;
      }
    }
    $format = implode('/', $format);
    $this->values = unpack($format, $this->bytes);
    $this->restoreEnums();
  }

  protected function restoreEnums() {
    foreach ($this->template as $field) {
      $type = $field[1];
      if (in_array($type, [Type::ENUM8, Type::ENUM16, Type::ENUM32])) {
        $name = $field[0];
        $enum = $field[2];
        $this->values[$name] = $enum[$this->values[$name]];
      }
    }
  }

}
