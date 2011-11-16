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

class Tipplefip {

	// Output data. Usually templated :-)
	private $output;
	
	// A keyed array with replacement values.
	private $data;

	function __construct($output = '', $data = array()) {
		$this->output = $output;
		$this->data = $data;
	}

	function parse() {

		foreach ($this->data as $key => $value) {
		  $txt = strtoupper($key);
		
		  // Straight replace.
		  if (strpos($this->output, "{{IF:{$txt}}}") !== FALSE) {
		    if (strlen($value)) {
		      $this->output = preg_replace("/\{\{IF:{$txt}\}\}(.*)\{\{{$txt}\}\}(.*)\{\{\/IF\}\}/", "\\1{$value}\\2", $this->output);
		    } else {
		      $this->output = preg_replace("/\{\{IF:{$txt}\}\}(.*)\{\{\/IF\}\}/", "", $this->output);
		    }
		  }
		
		  // URLencoded - yay.
		  if (strpos($this->output, "{{IF:{$txt}|H}}") !== FALSE) {
		    $hvalue = rawurlencode($value);
		    if (strlen($hvalue)) {
		      $this->output = preg_replace("/\{\{IF:{$txt}\}\}(.*)\{\{{$txt}\|H\}\}(.*)\{\{\/IF\}\}/", "\\1{$hvalue}\\2", $this->output);
		    } else {
		      $this->output = preg_replace("/\{\{IF:{$txt}\}\}(.*)\{\{\/IF\}\}/", "", $this->output);
		    }
		  }
		  $this->output = strtr($this->output, array("{{{$txt}}}" => $value));
		  $this->output = strtr($this->output, array("{{{$txt}|H}}" => rawurlencode($value)));
		}
		
		print $this->output;

	}

}
