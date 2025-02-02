<?php

namespace X11;

class Type {

  const BYTE = 1;
  const BOOL = 2;
  const INT8 = 3;
  const INT16 = 4;
  const INT32 = 5;
  const CARD8 = 6;
  const CARD16 = 7;
  const CARD32 = 8;
  const ENUM8 = 9;
  const ENUM16 = 10;
  const ENUM32 = 11;
  const STRING8 = 12;
  const PAD4 = 13;
  const VLIST = 14;
  const FLIST = 15;
  const BITMASK8 = 16;
  const BITMASK16 = 17;
  const BITMASK32 = 18;
  const EVENT = 19;
  const STR = 20;

  const LENGTH8 = 21;
  const LENGTH16 = 22;
  const LENGTH32 = 23;
  const LENGTH8_4 = 24;
  const LENGTH16_4 = 25;
  const LENGTH32_4 = 26;
  const LENGTH8_N = 27;
  const LENGTH16_N = 28;
  const LENGTH32_N = 29;
  const UNUSED = 30;

  const WINDOW = self::CARD32;
  const PIXMAP = self::CARD32;
  const CURSOR = self::CARD32;
  const FONT = self::CARD32;
  const GCONTEXT = self::CARD32;
  const COLORMAP = self::CARD32;
  const DRAWABLE = self::CARD32;
  const FONTABLE = self::CARD32;
  const ATOM = self::CARD32;
  const VISUALID = self::CARD32;
  const TIMESTAMP = self::CARD32;

  const KEYSYM = self::CARD32;
  const KEYCODE = self::CARD8;

  public static $format = [
    self::UNUSED => 'x',
    self::BYTE => 'C',
    self::BOOL => 'C',
    self::INT8 => 'c',
    self::INT16 => 's',
    self::INT32 => 'l',
    self::CARD8 => 'C',
    self::CARD16 => 'S',
    self::CARD32 => 'L',
    self::ENUM8 => 'C',
    self::ENUM16 => 'S',
    self::ENUM32 => 'L',
    self::STRING8 => 'a',
    self::BITMASK8 => 'C',
    self::BITMASK16 => 'S',
    self::BITMASK32 => 'L',
    self::LENGTH8 => 'C',
    self::LENGTH16 => 'S',
    self::LENGTH32 => 'L',
    self::LENGTH8_4 => 'C',
    self::LENGTH16_4 => 'S',
    self::LENGTH32_4 => 'L',
    self::LENGTH8_N => 'C',
    self::LENGTH16_N => 'S',
    self::LENGTH32_N => 'L'
  ];

  public static $size = [
    self::BYTE => 1,
    self::BOOL => 1,
    self::INT8 => 1,
    self::INT16 => 2,
    self::INT32 => 4,
    self::CARD8 => 1,
    self::CARD16 => 2,
    self::CARD32 => 4,
    self::ENUM8 => 1,
    self::ENUM16 => 2,
    self::ENUM32 => 4,
    self::BITMASK8 => 1,
    self::BITMASK16 => 2,
    self::BITMASK32 => 4,
    self::LENGTH8 => 1,
    self::LENGTH16 => 2,
    self::LENGTH32 => 4,
    self::LENGTH8_4 => 1,
    self::LENGTH16_4 => 2,
    self::LENGTH32_4 => 4,
    self::LENGTH8_N => 1,
    self::LENGTH16_N => 2,
    self::LENGTH32_N => 4
  ];

}
