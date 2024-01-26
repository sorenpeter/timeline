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

echo '<label id="refreshLabel" for="refreshProgress">Loading feeds followed by: '.$url.'</label><br>';
echo '<progress id="refreshProgress" value=""></progress>';

ob_flush();

const DEBUG_TIME_SECS = 300;
const PRODUCTION_TIME_SECS = 5;
$fileContent = getCachedFileContentsOrUpdate($url, PRODUCTION_TIME_SECS);
$fileContent = mb_convert_encoding($fileContent, 'UTF-8');
$fileLines = explode("\n", $fileContent);

// Build Following List
$twtFollowingList = [];

foreach ($fileLines as $currentLine) {
	if (str_starts_with($currentLine, '#')) {
		if (!is_null(getDoubleParameter('follow', $currentLine))) {
			$twtFollowingList[] = getDoubleParameter('follow', $currentLine);
		}
	}
}


/* Progress bar based on: https://github.com/w3shaman/php-progress-bar */

$i = 1;
$total = count($twtFollowingList);
foreach ($twtFollowingList as $following) {	
	$float = $i/$total;
    $percent = intval($float * 100)."%";
    
    // Javascript for updating the progress bar and information
    echo '<script language="javascript">
    		document.getElementById("refreshLabel").innerHTML = "Updating: '.$following[1].' ('.$i.'/'.$total.')";
    		document.getElementById("refreshProgress").value = "'.$float.'"; 
    		document.getElementById("refreshProgress").innerHTML = "'.$percent.'"; 
    	</script>';

    updateCachedFile($following[1]);

    ob_flush(); // Send output to browser immediately
    $i++;
}


// Tell user that the process is completed
echo '<script language="javascript">document.getElementById("refreshLabel").innerHTML="Refreshed '.$total.' feeds"</script>';

//header('Location: /');
exit();

