<?php
// TODO: Give a warning if the file is not found
$config = parse_ini_file('private/config.ini');

if ($config['debug_mode']) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$txt_file_path = $config['txt_file_path'];

require_once('partials/base.php');

/*
if (!has_valid_session()) {
	header('Location: login.php');
	exit();
}
*/

if (isset($_POST['url'])) {
	$url = trim(filter_input(INPUT_POST, 'url'));
	$nick = trim(filter_input(INPUT_POST, 'nick'));

	if (!$url or !$nick) {
		die('Fill url and nick.');
	}

	if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
		die('Not a valid URL');
	}

	if (!file_exists($txt_file_path)) {
		die('twtxt.txt file does not exist. Check your config.');
	}

	$contents = file_get_contents($txt_file_path);

	# Insert new follows before the marker #~~~#
	define('NEW_TWT_MARKER', "#~~~#\n");

	$follow_str_to_insert = "# follow = $nick $url";

	if (strpos($contents, NEW_TWT_MARKER) !== false) {
		// Add the previous marker
		// Take note that doesn't not work if twtxt file has CRLF line ending
		// (which is wrong anyway)
		$follow_str =  $follow_str_to_insert . "\n" . NEW_TWT_MARKER;
		$contents = str_replace(NEW_TWT_MARKER, $follow_str, $contents);
	} else {
		die('Could not insert the follower into the twtxt.txt file. Check that the marker exists.');
	}

	// TODO: Add error handling if write to the file fails
	// For example due to permissions problems
	// https://www.w3docs.com/snippets/php/how-can-i-handle-the-warning-of-file-get-contents-function-in-php.html
	$file_write_result = file_put_contents($txt_file_path, $contents);

	header('Refresh:0; url=.');
	exit;
} else { ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>twtxt</title>
	<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<h1><a href=".">twtxt</a></h1>
	<form method="POST" class="column">
		<div id="follow">
			<label for="url">URL to follow</label>
			<br>
			<input type="url" id="url" name="url" class="input" size="50" autocomplete="off" required>
			<br>
			<label for="nick">Nick</label>
			<br>
			<input type="text" id="nick" name="nick" class="input" size="50" autocomplete="off" required>
			<br>
			<input type="submit" value="Follow" class="btn">
		</div>
	</form>
</body>
</html>
<?php } ?>
