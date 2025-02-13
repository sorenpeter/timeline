<?php

// https://www.w3schools.io/ini-read-write-php/

$config = parse_ini_file("config.ini");

print_r("<pre>");
print_r($config);
print_r("</pre>");

if ($config !== false) {

echo '<h1>Settings for Timeline</h1><form>';

// TODO: Hardcode each for field and set types
foreach ($config as $key => $value) { 
	echo '<p><label for="'.$key.'">'.$key.':</label><br>';
	echo '<input type="text" id="'.$key.'" name="'.$key.'" value="'.$value.'"></p>';
}

echo '<input type="submit" value="Save"></form>';

} else {
	echo 'Read INI file Failed.';
} 


