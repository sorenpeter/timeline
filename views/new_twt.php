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

	/*if (!date_default_timezone_set($timezone)) {
		date_default_timezone_set('UTC');
	}*/ // Turned this off, so now the server need to have set the right timezone, seem to work for CET 

	//$datetime = gmdate('Y-m-d\TH:i:s\Z', $date->format('U'));
	//$twt = $datetime . "\t$new_post\n";
	//$twt = date('c') . "\t$new_post\n";
	$datetime = date('Y-m-d\TH:i:sp'); // abracting to be used for webmentions
	$twt = "\n" . $datetime . "\t" .$new_post; // NB: only works with PHP 8

	// TODO: Delete?
	/*if (strpos($contents, NEW_TWT_MARKER) !== false) {
		// Add the previous marker
		// Take note that doesn't not work if twtxt file has CRLF line ending
		// (which is wrong anyway)
		$twt = NEW_TWT_MARKER . $twt;
		$contents = str_replace(NEW_TWT_MARKER, $twt, $contents);
	} else {
		// Fall back if the marker is not found.
		$contents .= $twt;
	}*/
	
	// Append twt at the end of file
	$contents .= $twt;
	
// TODO: Add error handling if write to the file fails
	// For example due to permissions problems
	// https://www.w3docs.com/snippets/php/how-can-i-handle-the-warning-of-file-get-contents-function-in-php.html

	$file_write_result = file_put_contents($txt_file_path, $contents); 
// TODO: replace with file_put_contents($logfile, $log, FILE_APPEND)  -- https://www.w3schools.com/php/func_filesystem_file_put_contents.asp

	// Send webmentions (TODO: move to it own file?)
	$new_mentions = getMentionsFromTwt($twt); 

	foreach ($new_mentions as $mention) {
		//print_r(getMentionsFromTwt($twt));
		//echo $mention["nick"] . " from " . $mention["url"]."<br>";

		// TODO: detect endpoint via $mention["url"]

		$targets_webmention_endpoint = "https://darch.dk/timeline/webmention";

		$your_url = "https://darch.dk/twtxt.txt#:~:text=".$datetime;
		//$your_url = "https://darch.dk/twtxt.txt#:~:text=2024-03-16T20:38:31Z";
		$target_url = $mention["url"];

		$payload = "source=".$your_url."&target=".$target_url;

		echo $payload;
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $targets_webmention_endpoint);
		curl_setopt($curl, CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
		$data = curl_exec($curl);
		curl_close($curl);
	}

	//header('Refresh:0; url=.');
	header("Location: refresh?url=".$public_txt_url); // Trying to fix issue with douple posting
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
