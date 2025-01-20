<?php

namespace X11;

class Type {

  const ID = 0; // WINDOW, PIXMAP, CURSOR, FONT, GCONTEXT, COLORMAP, DRAWABLE, FONTABLE, ATOM, VISUALID
  const BYTE = 1;
  const BOOL = 1;
  const INT8 = 2;
  const INT16 = 3;
  const INT32 = 4;
  const CARD8 = 5;
  const CARD16 = 6;
  const CARD32 = 7;
  const ENUM8 = 11;
  const ENUM16 = 12;
  const ENUM32 = 13;
  const STRING8 = 14;
  const PAD4 = 15;
  const LIST = 16;
  const EVENT = 17;

  const BITMASK = self::CARD32;
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
    self::ID => 'L',
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
    self::STRING8 => 'a*'
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
