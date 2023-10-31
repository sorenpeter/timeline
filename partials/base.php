<?php
# Shows the timeline for a user
declare(strict_types=1);

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
#

require_once("libs/session.php");
require_once('libs/twtxt.php');
require_once('libs/hash.php');
require_once('libs/Slimdown.php');

const TWTS_PER_PAGE = 50;

// TODO: Move twts per page to config.ini
// Add a fallback if the number tis invalid (it should be between 1 and 999)
$config = parse_ini_file('private/config.ini');
//$url = $config['public_txt_url'];

// TODO: Take the title from the config.ini
$title = "Timeline"; // Fallback, should be set in all views

// HACKED by sp@darch.dk 
    if(!empty($_GET['list'])) {
        $url = "https://darch.dk/twtxt-lists/".$_GET['list'];
    }
    else {
        $url = $config['public_txt_url'];
    }

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

date_default_timezone_set('UTC');

if (!empty($_GET['url'])) {
    $url = $_GET['url'];
}

if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
    die('Not a valid URL');
}

//$validSession = has_valid_session();
//echo("Valid session: $validSession");

$cacheRefreshTime = $config['cache_refresh_time'];
$fileContent = getCachedFileContentsOrUpdate($url, $cacheRefreshTime);

if ($fileContent === false) {
    die("$url couldn't be retrieved.");
}

$fileContent = mb_convert_encoding($fileContent, 'UTF-8');
$fileLines = explode("\n", $fileContent);
$twtFollowingList = [];


// Show twts only for URL in query request, else show user timeline

if (!empty($_GET['twts'])) { // Show twts for some user --> /profile
    $twtsURL = $_GET['twts'];
    if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
        die('Not a valid URL');
    }

    $parsedTwtxtFile = getTwtsFromTwtxtString($twtsURL);
    if (!is_null($parsedTwtxtFile)) {
        $parsedTwtxtFiles[$parsedTwtxtFile->mainURL] = $parsedTwtxtFile;
    }

} else { // Show timeline for the URL --> / (home)
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

# Show individual posts
if (!empty($_GET['hash'])) {
    $hash = $_GET['hash'];
    $twts = array_filter($twts, function($twt) use ($hash) {
        return $twt->hash === $hash || $twt->replyToHash === $hash;
    });
}

krsort($twts, SORT_NUMERIC);

if (!empty($_GET['hash'])) {
    $twts = array_reverse($twts, true);
}

$page = 1;
if (!empty($_GET['page'])) {
    $page = intval($_GET['page']);
}

$startingTwt = (($page - 1) * TWTS_PER_PAGE);
$twts = array_slice($twts, $startingTwt, TWTS_PER_PAGE);
