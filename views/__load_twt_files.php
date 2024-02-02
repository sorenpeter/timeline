<?php
# Gets the followers from an URL and then gets all the Followers twtxt.txt files
# Intended to be run in the background

require_once("libs/session.php"); // TODO: Move all to base.php
require_once('libs/twtxt.php');
require_once('libs/hash.php');

$config = parse_ini_file('private/config.ini');

if (!isset($_SESSION['password'])) {
	header('Location: ./login');
	exit();
}

$max_execution_time = intval($config['max_execution_time']);
if ($max_execution_time < 1) {
	$max_execution_time = 1;
}

ini_set('max_execution_time', $max_execution_time);

#ob_start();

$config = parse_ini_file('private/config.ini');
$url = $config['public_txt_url'];

if (!empty($_GET['url'])) {
	$url = $_GET['url'];
}

if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
	die('Not a valid URL');
}

echo "Loading URL: $url<br>\n<br>\n";
#ob_flush();

const DEBUG_TIME_SECS = 300;
const PRODUCTION_TIME_SECS = 5;
$fileContent = getCachedFileContentsOrUpdate($url, PRODUCTION_TIME_SECS);
$fileContent = mb_convert_encoding($fileContent, 'UTF-8');

$fileLines = explode("\n", $fileContent);

$twtFollowingList = [];
foreach ($fileLines as $currentLine) {
	if (str_starts_with($currentLine, '#')) {
		if (!is_null(getDoubleParameter('follow', $currentLine))) {
			$twtFollowingList[] = getDoubleParameter('follow', $currentLine);
		}
	}
}

# Load all the files
# Save a flag to know it's loading files in the background
foreach ($twtFollowingList as $following) {
	echo "Updating: $following[1]<br>\n";
	#ob_flush();
	updateCachedFile($following[1]);
}
echo 'Finished';
#ob_flush();

header('Location: /');
exit();
