<?php
/**
 * tipplefip - teeny tiny php based template engine.
 *
 * Named after the silly way to pronounce .tpl.php
 *
 * This snippet uses a keyed input array to replace template
 * values in a text string (or file).
 *
 * $output = "My name is {{NAME}}.";
 * $data = array('name' => 'Cafuego');
 *   --> "My name is Cafuego.";
 *
 * $output = "{{IF:SURNAME}}My surname is {{SURNAME}}.{{/IF}}";
 * $data = array('name' => 'Cafuego');
 *   --> "".
 *
 * $output = "Click this <a href='{{HREF|H}}'>{{LINK}}</a>.";
 * $data = array('link' => 'clicky thing', 'href' => '/a test link');
 *   --> "Click this <a href='%2Fa%20test%20link'>clicky thing</a>.".
 */

// Output data. Usually templated :-)
$output = '';

// A keyed array with replacement values.
$data = array();

foreach ($data as $key => $value) {
  $txt = strtoupper($key);

  // Straight replace.
  if (strpos($output, "{{IF:{$txt}}}") !== FALSE) {
    if (strlen($value)) {
      $output = preg_replace("/\{\{IF:{$txt}\}\}(.*)\{\{{$txt}\}\}(.*)\{\{\/IF\}\}/", "\\1{$value}\\2", $output);
    } else {
      $output = preg_replace("/\{\{IF:{$txt}\}\}(.*)\{\{\/IF\}\}/", "", $output);
    }
  }

  // URLencoded - yay.
  if (strpos($output, "{{IF:{$txt}|H}}") !== FALSE) {
    $hvalue = rawurlencode($value);
    if (strlen($hvalue)) {
      $output = preg_replace("/\{\{IF:{$txt}\}\}(.*)\{\{{$txt}\|H\}\}(.*)\{\{\/IF\}\}/", "\\1{$hvalue}\\2", $output);
    } else {
      $output = preg_replace("/\{\{IF:{$txt}\}\}(.*)\{\{\/IF\}\}/", "", $output);
    }
  }
  $output = strtr($output, array("{{{$txt}}}" => $value));
  $output = strtr($output, array("{{{$txt}|H}}" => rawurlencode($value)));
}

print $output;
