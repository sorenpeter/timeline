<?php
// TODO: Give a warning if the file is not found
$config = parse_ini_file('private/config.ini');

if ($config['debug_mode']) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

require_once('session.php');

if (!isset($_SESSION['valid_session'])) {
	$secretKey = $config['totp_secret'];
	$cookieVal = isSavedCookieValid($secretKey);

	if ($cookieVal === false) { # Valid cookie ?
		header('Location: login.php');
		exit();
	}
}

if (isset($_POST['submit'])) {
	$new_post = filter_input(INPUT_POST, 'new_post');
	// Replace new lines for Line separator character (U+2028)
	$new_post = str_replace("\n", "\u{2028}", $new_post);
	$new_post = str_replace("\r", '', $new_post);

	if ($new_post) {
		// Check if we have a point to insert the next Twt
		define('NEW_TWT_MARKER', "#~~~#\n");
		$contents = file_get_contents($txt_file_path);

		if (!date_default_timezone_set($timezone)) {
			date_default_timezone_set('UTC');
		}

		$twt = date('c') . "\t$new_post\n";

		if (strpos($contents, NEW_TWT_MARKER) !== false) {
			// Add the previous marker
			$twt = NEW_TWT_MARKER . $twt;
			$contents = str_replace(NEW_TWT_MARKER, $twt, $contents);
		} else {
			$contents .= $twt;
		}

		// TODO: Add error handling if write to the file fails
		// For example due to permissions problems
		$file_write_result = file_put_contents($txt_file_path, $contents);

		header('Refresh:0; url=.');
		exit;
	} else {
		echo "Oops something went wrong...\n\nCheck the error_log in the server";
		exit;
	}
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
			<div id="login">
				<?php if ($invalidURL) { ?>
					<div class="alert">URL is invalid, check it!</div><br>
				<?php } ?>
				<label for="fname">URL to twtxt.txt file</label>
				<br>
				<input type="text" id="url" name="url" class="input" autocomplete="off"><br>
				<input type="submit" value="Add URL" class="btn">
			</div>
		</form>
	</body>

	</html>
<?php } ?>