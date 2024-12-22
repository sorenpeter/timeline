<?php
$config = parse_ini_file('private/config.ini');

if (!date_default_timezone_set($config['timezone'])) {
	die('Not a valid timezone - Check your config.ini file');
}