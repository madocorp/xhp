<?php

namespace X11;

class PolyText16Request extends Request {

  public function __construct($drawable, $gc, $x, $y, $texts) {
    $opcode = 75;
    $template = [
      ['opcode', Type::BYTE],
      ['unused', Type::UNUSED, 1],
      ['requestLength', Type::CARD16],
      ['drawable', Type::DRAWABLE],
      ['gc', Type::GCONTEXT],
      ['x', Type::INT16],
      ['y', Type::INT16]
    ];
    $values = [
      'opcode' => $opcode,
      'drawable' => $drawable,
      'gc' => $gc,
      'x' => $x,
      'y' => $y
    ];
    $n = 0;
    foreach ($texts as $i => $item) {
      if (is_array($item)) {
        $template[] = ["text_{$i}.m", Type::CARD8];
        $template[] = ["text_{$i}.delta", Type::INT8];
        $template[] = ["text_{$i}.string", Type::STRING8, false];
        $v = array_values($item);
        $length = strlen($item[1]);
        $item[1] = mb_convert_encoding($item[1], "ISO-10646-UCS-2", "UTF-8");
        $values["text_{$i}.m"] = $length;
        $values["text_{$i}.delta"] = $item[0];
        $values["text_{$i}.string"] = $item[1];
        $n += $length * 2 + 2;
      } else {
        $template[] = ["text_{$i}.m", Type::CARD8];
        $template[] = ["text_{$i}.font", Type::CARD32];
        $values["text_{$i}.m"] = 255;
        if (Connection::machineByteOrder() == 0x6c) {
          $a = ($item & 0xff000000) >> 24;
          $b = ($item & 0xff0000) >> 16;
          $c = ($item & 0xff00) >> 8;
          $d = $item & 0xff;
          $item = ($d << 24) | ($c << 16) | ($b << 8) | $a;
        }
        $values["text_{$i}.font"] = $item;
        $n += 5;
      }
    }
    $template[] = ['pad', Type::UNUSED, Connection::pad4($n)];
    $this->sendRequest($template, $values);
  }

}
