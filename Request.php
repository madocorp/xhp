<?php

namespace X11;

class Request {

  protected function sendRequest($template, $values) {
    $atb = new ArrayToBytes($template, $values);
    $bytes = $atb->get();
    if (DEBUG) {
      $requestName = basename(str_replace('\\', '/', get_class($this)));
      Debug::request($atb->getTemplate(), $atb->getValues(), $bytes, $requestName);
    }
    Connection::write($bytes);
  }

  protected function waitForResponse($length) {
    while (true) {
      $bytes = Connection::read(32);
      $header = unpack('Ctype', $bytes);
      if ($header['type'] == 0) {
        Error::handle($bytes);
      } else  if ($header['type'] > 1) {
        Event::handle($bytes);
      } else {
        if ($length > 32) {
          $bytes .= Connection::read($length - 32);
        }
        break;
      }
    }
    return $bytes;
  }

  protected function receiveResponse($template, $start = true) {
    $bta = new BytesToArray($template);
    $length = $bta->getLength();
    if ($start) {
      $bytes = self::waitForResponse($length);
    } else {
      $bytes = Connection::read($length);
    }
    $bta->parse($bytes);
    $response = $bta->get();
    if (DEBUG) {
      Debug::response($response);
    }
    unset($response['unused']);
    if (count($response) == 1) {
      $response = reset($response);
    }
    return $response;
  }

}
