<?php
# Shows the timeline for a user

declare (strict_types = 1);

# Parameters
#
# url(string): Gets
# Default: public_txt_url in config.ini
#
# timeline_url(string) = Gets the timeline for that specificed URL (twtxt)
# Default: public_txt_url in config.ini
#
# page(int):
# Default: Page 1 of N
# If page is higher than N, shows nothing
#
# hash(string) =

require_once 'libs/session.php';
require_once 'libs/twtxt.php';
require_once 'libs/hash.php';
require_once 'libs/Parsedown.php';
require_once 'libs/load_timezone.php';

// TODO: Move twts per page to config.ini
// Add a fallback if the number is invalid (it should be between 1 and 999)
const TWTS_PER_PAGE = 50;

$title = $config['site_title'] ?? "Timeline";

// HACKED by sp@darch.dk
$url = !empty($_GET['list']) ? $baseURL.$_GET['list'] : $config['public_txt_url'];

/*
if(isset($_GET['selectList'])){
    if(!empty($_GET['lists'])) {
        $url = "https://darch.dk/twtxt-lists/".$_GET['lists'];
    }
    // else {
    //     $url = $config['public_txt_url'];
    // }
} else {
    $url = $config['public_txt_url'];
    //$url = "https://darch.dk/twtxt-lists/twtxt.txt";
}
*/

$url = !empty($_GET['url']) ? filter_var($_GET['url'], FILTER_SANITIZE_URL) : $url;

if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
	die('Not a valid URL');
}

$cacheRefreshTime = $config['cache_refresh_time'];
$fileContent = getCachedFileContentsOrUpdate($url, $cacheRefreshTime);

if ($fileContent === false) {
	die("$url couldn't be retrieved.");
}

$fileContent = mb_convert_encoding($fileContent, 'UTF-8');
$fileLines = explode("\n", $fileContent);
$twtFollowingList = [];

/*
if (!empty($_GET['url'])) { // Show profile for some user
	$twtsURL = $_GET['url'];
	if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
		die('Not a valid URL');
	}

	$parsedTwtxtFile = getTwtsFromTwtxtString($twtsURL);
	if (!is_null($parsedTwtxtFile)) {
		$parsedTwtxtFiles[$parsedTwtxtFile->mainURL] = $parsedTwtxtFile;
	}
*/

if (!empty($twtsURL)) {
	$parsedTwtxtFile = getTwtsFromTwtxtString($twtsURL);
	if (!is_null($parsedTwtxtFile)) {
		$parsedTwtxtFiles[$parsedTwtxtFile->mainURL] = $parsedTwtxtFile;
	}
} else { // Show timeline for the URL
	$parsedTwtxtFiles = [];
	foreach ($fileLines as $currentLine) {
		if (str_starts_with($currentLine, '#')) {
			if (!is_null(getDoubleParameter('follow', $currentLine))) {
				$follow = getDoubleParameter('follow', $currentLine);
				$twtFollowingList[] = $follow;

				// Read the parsed files if in Cache
				$followURL = $follow[1];
				$parsedTwtxtFile = getTwtsFromTwtxtString($followURL);
				if (!is_null($parsedTwtxtFile)) {
					$parsedTwtxtFiles[$parsedTwtxtFile->mainURL] = $parsedTwtxtFile;
				}
			}
		}
	}
}

$twts = [];

# Combine all the followers twts
foreach ($parsedTwtxtFiles as $currentTwtFile) {
	if (!is_null($currentTwtFile)) {
		$twts += $currentTwtFile->twts;
	}
}

// Search / filter on tags (or anything within a twt actually)
// Base on hash filter below and on code from: https://social.dfaria.eu/search

// TODO: Move to after rendering of tag-cloud to get all tags rendered
/*
if (!empty($_GET['search'])) {
	$search = $_GET['search'];

    $pattern = preg_quote($search, '/');
    $pattern = "/^.*$pattern.*\$/mi";

	$twts = array_filter($twts, function ($twt) use ($pattern) {
		return preg_match($pattern, $twt->content);
	});
}
*/

// TODO: (re)move or rename `?hash=` to something/where else?

if (!empty($_GET['hash'])) {
	$hash = $_GET['hash'];
	$twts = array_filter($twts, function ($twt) use ($hash) {
		return $twt->hash === $hash || $twt->replyToHash === $hash;
	});
}

krsort($twts, SORT_NUMERIC);

if (!empty($_GET['hash'])) {
	$twts = array_reverse($twts, true);
}

// Pagnation

//$twts_per_page = $config['twts_per_page'];

$page = 1;
if (!empty($_GET['page'])) {
	$page = intval($_GET['page']);
}

// If we should paginate our twts list
if (!empty($paginateTwts)) {
	$startingTwt = (($page - 1) * TWTS_PER_PAGE);
	$twts = array_slice($twts, $startingTwt, TWTS_PER_PAGE);
}

$baseURL = str_replace("/index.php", "", $_SERVER['SCRIPT_NAME']);
