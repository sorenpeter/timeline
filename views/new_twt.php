<?php
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
$public_txt_url = $config['public_txt_url'];

$timezone = $config['timezone'];
require_once 'libs/load_timezone.php';

//session_start(); // Post-Redirect-Get Pattern based on: https://icodemag.com/prg-pattern-in-php-what-why-and-how/

if (isset($_POST['submit'])) {
	$new_post = filter_input(INPUT_POST, 'new_post');
	$new_post = trim($new_post);

	// Replace new lines for Line separator character (U+2028)
	$new_post = str_replace("\n", "\u{2028}", $new_post);
	// Remove Carriage return if needed
	$new_post = str_replace("\r", '', $new_post);

	// TODO: If twt is empty, show an error

	// Check if we have a point to insert the next Twt
	define('NEW_TWT_MARKER', "#~~~#\n");

	// if (!file_exists($txt_file_path)) {
	// 	echo '';
	// 	exit;
	// }

	if(!file_exists($txt_file_path)) exit("<p class='notice'>twtxt.txt file does not exist. Check your <code>config.ini</code></p>");

	$contents = file_get_contents($txt_file_path);

	/*if (!date_default_timezone_set($timezone)) {
		date_default_timezone_set('UTC');
	}*/ // Turned this off, so now the server need to have set the right timezone, seem to work for CET

	//$datetime = gmdate('Y-m-d\TH:i:s\Z', $date->format('U'));
	//$twt = $datetime . "\t$new_post\n";
	//$twt = date('c') . "\t$new_post\n";
	$datetime = date('Y-m-d\TH:i:sp'); // abracting to be used for webmentions
	$twt = "\n$datetime\t$new_post"; // NB: only works with PHP 8

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

	// Send webmentions
	include_once 'partials/webmentions_send.php';

	//header('Refresh:0; url=.');
	//header("Location: refresh?url=".$public_txt_url); // Trying to fix issue with douple posting
	//exit;

	//$_SESSION["message"] = $msg;
	header('Location: '.$_SERVER['QUERY_STRING']);
	exit;

} else {
	require_once "partials/base.php";
	$title = "New post - $title";
	include_once 'partials/header.php';

	$textareaValue = $textareaValue ?? '';

	if (isset($_GET['hash'])) {
		$hash = $_GET['hash'];
		$textareaValue = "(#$hash) ";

		// COPY from conv.php
		// TODO: make into a partial or global function
		// Get the hashes (both post and replies) as $hash from the router and return an inverted list
		$twt_op = array_filter($twts, function($twt) use ($hash) {
			return $twt->hash === $hash; //|| $twt->replyToHash === $hash;
		});
		//$twts = array_reverse($twts, true);

		//$textareaValue .= print_r($twts);
		//$textareaValue .= $twts["nick"];

		include_once 'partials/timeline.php';
	}
?>

<article id="new_twt">
	<form method="POST">
		<div id="posting">	
			<div id="toolbar">
				<a href="./upload" target="_blank" class="upload-link" title="Upload images"><i class="fa fa-upload" aria-hidden="true"></i></a>
			</div>
			<textarea class="textinput" id="new_post" name="new_post"
				rows="4" cols="100" autofocus required onfocus="var val=this.value; this.value=''; this.value= val;"
				placeholder="Your twt"><?= $textareaValue ?></textarea>
			<!-- <br> -->
			<input type="submit" value="Post" name="submit">
		</div>

		<script type="text/javascript">
		  var tinyMDE = new TinyMDE.Editor({
		  	element: "new_post",
		  	// content: "Type your twt"
		  });
		  var commandBar = new TinyMDE.CommandBar({
		    element: "toolbar",
		    editor: tinyMDE,
		    commands: ['bold', 'italic', 'strikethrough', 'ul', 'ol',  'blockquote', 'code', '|', 'insertLink', 'insertImage'],

		  });
		</script>

	</form>
</article>

<!-- PHP: GET TIMELINE  --><?php include_once 'partials/timeline.php'; ?>

<!-- PHP: GET FOOTER  --><?php include_once 'partials/footer.php'; ?>

<?php } ?>
