<?php

echo parse_css('default2.css');

function parse_css($css_url) {
	$result	= array();

	if(strlen($css_url) && strpos($css_url, '://') === false) {
		if(strpos($css_url, '/') === 0) // against '/main.css'
			$css_url = substr($css_url, 1);
		
		if(($css = file_get_contents($css_url)) !== false) {
			// strip comments
			$css = preg_replace("/\/\*(.*)?\*\//Usi", "", $css);
			// parse css
			$partss =  explode("}", $css);
			$parts = array_pop($partss);
			//print_r($partss);
			if(sizeof($partss) > 0) {
				foreach($partss as $part) {
					list($s_key, $s_code) = explode("{", $part);
					$keys = explode(",", trim($s_key));
					//print_r($keys);
					if(sizeof($keys) > 0) {
						foreach($keys as $key) {
							if(strlen($key) > 0) {
							//echo $key;
							list($tmp, $key) = explode(".", $key);
							//list($key, $tmp) = explode(" ", $key);
							//list($key, $tmp) = explode(":", $key);
							//list($key, $tmp) = explode("#", $key);

							$key = trim($key);
							//echo "The key is " . $key . "\n";
								}
							if(strlen($key))
								$result[$key]	= true;
						}
					}
				}
			}

			$result = array_keys($result);
		}
	}

	return implode(',', $result);
}
?>