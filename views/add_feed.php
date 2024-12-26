<?php
require_once 'partials/base.php';
require_once 'partials/webfinger_lookup.php';
require_once 'libs/session.php';

checkValidSessionOrRedirectToLogin();

// TODO: Give a warning if the file is not found
$config = parse_ini_file('private/config.ini');

if ($config['debug_mode']) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$txt_file_path = $config['txt_file_path'];

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

	<?php
	$title = "Add feed - " . $title;

	include 'partials/header.php';
	?>

	<h2>Webfinger lookup</h2>

	<form method="post" action="">
		<label>Check if a webfinger handle has a link to a twtxt.txt feed</label>
		<input type="text" name="webfinger" size="50" autocomplete="off" required placeholder="name@example.com" value="<?= $wf_request; ?>">
		<br>
		<input type="submit" name="submit" value="Lookup"><br>
	</form>

	<?= $wf_error; ?>

	<h1>Add a new feed to follow</h1>

	<form method="POST" class="column">
		<div id="follow">
			<label for="nick">Nick</label>
			<input type="text" id="nick" name="nick" class="input" size="50" autocomplete="off" required value="<?= $wf_nick; ?>">
			<label for="url">URL to follow</label>
			<input type="url" id="url" name="url" class="input" size="50" autocomplete="off" required value="<?= $wf_url; ?>">
			<br>
			<input type="submit" value="Follow" class="btn">
		</div>
	</form>

	<!-- PHP: GET FOOTER  --><?php include 'partials/footer.php'; ?>

<?php } ?>