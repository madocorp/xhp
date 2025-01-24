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
  const EVENT = 16;
  const BITMASK8 = 17;
  const BITMASK16 = 18;
  const BITMASK32 = 19;

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

  public static $format = [
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
    self::STRING8 => 'a*',
    self::BITMASK8 => 'C',
    self::BITMASK16 => 'S',
    self::BITMASK32 => 'L'
  ];

  public static $size = [
    'c' => 1,
    'C' => 1,
    's' => 2,
    'S' => 2,
    'l' => 4,
    'L' => 4
  ];

}
