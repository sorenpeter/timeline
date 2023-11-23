<?php
// TODO: Give a warning if the file is not found
$config = parse_ini_file('private/config.ini');

if ($config['debug_mode']) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$txt_file_path = $config['txt_file_path'];
$public_txt_url = $config['public_txt_url'];
$timezone = $config['timezone'];

require_once('libs/session.php');

// if (!has_valid_session()) {
// 	header('Location: /login.php');
// 	exit();
// }

if (!isset($_SESSION['password'])) {
	header('Location: ./login');
	exit();
}

$textareaValue = '';
if (isset($_GET['hash'])) {
	$hash = $_GET['hash'];
	$textareaValue = "(#$hash) ";
}

if (isset($_POST['submit'])) {
	$new_post = filter_input(INPUT_POST, 'new_post');
	$new_post = trim($new_post);

	// Replace new lines for Line separator character (U+2028)
	$new_post = str_replace("\n", "\u{2028}", $new_post);
	// Remove Carriage return if needed
	$new_post = str_replace("\r", '', $new_post);

	// TODO: If twt is emply, show an error
	/*
	if ($new_post) {
	}
	*/

	// Check if we have a point to insert the next Twt
	define('NEW_TWT_MARKER', "#~~~#\n");

	if (!file_exists($txt_file_path)) {
		echo 'twtxt.txt file does not exist. Check your config.';
		exit;
	}

	$contents = file_get_contents($txt_file_path);

	if (!date_default_timezone_set($timezone)) {
		date_default_timezone_set('UTC');
	}

	//$datetime = gmdate('Y-m-d\TH:i:s\Z', $date->format('U'));
	//$twt = $datetime . "\t$new_post\n";
	$twt = date('c') . "\t$new_post\n";


	if (strpos($contents, NEW_TWT_MARKER) !== false) {
		// Add the previous marker
		// Take note that doesn't not work if twtxt file has CRLF line ending
		// (which is wrong anyway)
		$twt = NEW_TWT_MARKER . $twt;
		$contents = str_replace(NEW_TWT_MARKER, $twt, $contents);
	} else {
		// Fall back if the marker is not found.
		$contents .= $twt;
	}

	// TODO: Add error handling if write to the file fails
	// For example due to permissions problems
	// https://www.w3docs.com/snippets/php/how-can-i-handle-the-warning-of-file-get-contents-function-in-php.html
	$file_write_result = file_put_contents($txt_file_path, $contents);

	header('Refresh:0; url=.');
	exit;

} else {

require_once("partials/base.php");

$title = "New post - ".$title;

include_once 'partials/header.php';
?>

<article id="new_twt">
	<form method="POST">
		<div id="posting">
			<textarea class="textinput" id="new_post" name="new_post"
				rows="4" cols="100" autofocus required
				placeholder="Your twt"><?= $textareaValue ?></textarea>
			<!-- <br> -->
			<input type="submit" value="Post" name="submit">
		</div>
	</form>
</article>

<!-- PHP: GET TIMELINE  --><?php include_once 'partials/timeline.php' ?>

<!-- PHP: GET FOOTER  --><?php include_once 'partials/footer.php'; ?>

<?php } ?>
