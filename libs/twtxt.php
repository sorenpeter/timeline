<?php

declare(strict_types=1);

$config = parse_ini_file('private/config.ini');

if ($config['debug_mode']) {
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);
}

$agentVersion = trim(file_get_contents('./VERSION'));

class TwtxtFile {
	public $mainURL = ''; // First found URL
	public $URLs = [];
	public $nick = '';
	public $avatar = '';
	public $emoji = '';
	public $description = '';
	public $lang = 'en'; // Default language
	public $links = [];
	public $following = [];
	public $twts = [];
}

class Twt {
	public $originalTwtStr;
	public $hash;
	public $timestamp;
	public $fullDate;
	public $displayDate;
	public $content;
	public $replyToHash;
	public $mentions;
	public $avatar;
	public $emoji;
	public $nick;
	public $mainURL;
	public $images = [];
	public $tags = [];
}

# https://stackoverflow.com/a/39360281/13173382
# Confirm that this temorary fix is not skipping something
/*
stream_context_set_default([
	'ssl'                => [
		'peer_name'          => 'generic-server',
		'verify_peer'        => FALSE,
		'verify_peer_name'   => FALSE,
		'allow_self_signed'  => TRUE
		]
	]
);
curl_setopt($curl, CURLOPT_SSLVERSION, 4);
*/

/**
 * The function searches for a key-value pair in a string and returns the value if found.
 *
 * @param keyToFind The key we want to find in the string.
 * @param string The string in which to search for the key-value pair.
 *
 * @return the value of the key that matches the given keyToFind in the given string. If a match is
 * found, the function returns the value of the key as a string after trimming any whitespace. If no
 * match is found, the function returns null.
 */
function getSingleParameter($keyToFind, $string) {
	if (!str_contains($string, $keyToFind)) {
		return null;
	}

	$pattern = '/\s*(?<!\S)' . $keyToFind . '\s*=\s*([^#\n]+)/';
	// Fix: not machting with nick as in: `# follow = dbucklin@www.davebucklin.com https://www.davebucklin.com/twtxt.txt?nick=dbucklin`
	//$pattern = '/\s*' . $keyToFind . '\s*=\s*([^#\n]+)/';
	//$pattern = '/\s*' . $keyToFind . '\s*=\s*([^\s#]+)/'; // Only matches the first word
	preg_match($pattern, $string, $matches);

	if (isset($matches[1])) {
		return trim($matches[1]);
	}

	return null;
}

function getDoubleParameter($keywordToFind, $string) {
	// Returns string or null
	$pattern = '/#\s*' . preg_quote($keywordToFind, '/') . '\s*=\s*(\S+)\s*(\S+)/';
	// Matches "# <keyword> = <value> <value>"
	preg_match($pattern, $string, $matches);

	if (isset($matches[1]) && isset($matches[2])) {
		$result = array($matches[1], $matches[2]);
		return $result;
	}

	return null;
}

function getReplyHashFromTwt(string $twtString): string {
	// Extract the text between parentheses using regular expressions
	$pattern = '/\(#([^\)]+)\)/'; // Matches "(#<text>)"
	preg_match($pattern, $twtString, $matches);

	if (isset($matches[1])) {
		$textBetweenParentheses = $matches[1];
		return $textBetweenParentheses;
	}

	return '';
}

function getImagesFromTwt(string $twtString) {
	$pattern = '/(<img[^>]+>)/i';
	preg_match_all($pattern, $twtString, $matches, PREG_SET_ORDER);

	$result = array();

	foreach ($matches as $match) {
		$result[] = array($match[0]);
	}

	return $result;
}

function getTagsFromTwt(string $twtString) {
	//$pattern = '/(?<!\()\B#\w+(?!\))/iu';
	//$pattern = '/(?<=\B)#(\w+)/';
	$pattern = '/(?<=\B)#([\p{L}\p{N}_]+)/u';
	//$pattern = '/(?<=\s)#(\w+)/';
	//$pattern = '/\s#(\w+)/';
	//$pattern = "/\(#\w{7}\)/";
	//$pattern = '/(?<=\s|^)#(\w+)/';
	// TODO: Fix so it does not match with url#fragments (\B vs \s)
	// But for some reason this does not work: '/(?<!\()\s#\w+(?!\))/iu';

	preg_match_all($pattern, $twtString, $matches, PREG_SET_ORDER);

	$result = array();

	foreach ($matches as $match) {
		$result[] = array($match[0]);
	}

	return $result;
}

function getMentionsFromTwt(string $twtString) {
	$pattern = '/@<([^>]+)\s([^>]+)>/'; // Matches "@<nick url>"
	preg_match_all($pattern, $twtString, $matches, PREG_SET_ORDER);

	$result = array();

	foreach ($matches as $match) {
		$nick = $match[1];
		$url = $match[2];
		$result[] = array("nick" => $nick, "url" => $url);
	}

	return $result;
}

function replaceMentionsFromTwt(string $twtString): string {
	// Example input: 'Hello @<eapl.mx https://eapl.mx/twtxt.txt>, how are you? @<nick https://server.com/something/twtxt.txt>';
	// Example output: Hello <a href="?url=https://eapl.mx/twtxt.txt">@eapl.mx@eapl.mx/twtxt.txt</a>, how are you? <a href="?url=https://server.com/something/twtxt.txt">@nick@server.com/something/twtxt.txt</a>

	$pattern = '/@<([^ ]+)\s([^>]+)>/';
	//$replacement = '<a href="/?url=$2">@$1</a>';
	$replacement = '<a href="' . str_replace("/index.php", "", $_SERVER["SCRIPT_NAME"]) . '/profile?url=$2">@$1</a>';
	$replacement .= '<a href="$2" class="webmention"></a>'; // Adds a hidden link direcly to the twtxt.txt of the mentioned target
	#$twtString = '@<nick https://eapl.mx/twtxt.txt>';
	#$pattern = '/@<([^ ]+) ([^>]+)>/';
	#$replacement = '@$1';
	$result = preg_replace($pattern, $replacement, $twtString);
	return $result;

	// from https://github.com/hxii/picoblog/blob/master/picoblog.php
	//$pattern = '/\@<([a-zA-Z0-9\.]+)\W+(https?:\/\/[^>]+)>/';
	//return preg_replace($pattern,'<a href="$2">@$1</a>',$twtString);
}

function replaceLinksFromTwt(string $twtString) {

	// TODO: Make this NOT match with `inline code` to avoid links in code-snippets
	// 1. Look into how yarnd handles this

	// Regular expression pattern to match URLs
	//$pattern = '/(?<!\S)(\b(https?|ftp|gemini|spartan|gopher):\/\/\S+|\b(?!:\/\/)\w+(?:\.\w+)+(?:\/\S+)?)(?!\S)/';
	$pattern = '/(?<!\S)(?<!href="|">)(?<!src=\")((http|ftp)+(s)?:\/\/[^<>\s]+)/is';

	// Replace URLs with clickable links
	$replacement = '<a href="$1">$1</a>';
	$result = preg_replace($pattern, $replacement, $twtString);

	return $result;
}

function replaceMarkdownLinksFromTwt(string $twtString) {
	$pattern = '/\[([^\]]+)\]\(([^)]+)\)/';

	$replacement = '<a href="$2">$1</a>';
	$result = preg_replace($pattern, $replacement, $twtString);

	return $result;
}

function replaceImagesFromTwt(string $twtString) {
	$pattern = '/!\[(.*?)\]\((.*?)\)/';
	//$replacement = '<img src="$2" alt="$1">';
	$replacement = '<a href="$2"><img src="$2" alt="$1"></a>';
	$result = preg_replace($pattern, $replacement, $twtString);

	return $result;
}

function replaceTagsFromTwt(string $twtString) {
	//$pattern = '/#(\w+)?/';
	//$pattern = '/(?<=\s)#(\w+)/';
	$pattern = '/(?<=\B)#([\p{L}\p{N}_]+)/u';

	//$replacement = '<a href="#">#\1</a>'; // Dummy link
	$replacement = '<a href="?search=$1" class="tag">#${1}</a>';
	$result = preg_replace($pattern, $replacement, $twtString);

	return $result;
}

function embedYoutubeFromTwt(string $twtString) {

	// original regex source: https://gist.github.com/afeld/1254889#gistcomment-1253992
	$pattern = '/(?:youtube(?:-nocookie)?\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/mi';

	if (preg_match_all($pattern, $twtString, $youtubeLinks)) {

		$youtubeLinks = array_unique($youtubeLinks[1]); // Remove dublicate cause by raw URLs conceverter to links

		foreach ($youtubeLinks as $videoID) {
			$twtString .= '<iframe loading="lazy" src="https://www.youtube.com/embed/' . $videoID . '" class="embed-video" allow="encrypted-media" title="" allowfullscreen="allowfullscreen" frameborder="0"></iframe>';
		}
	}

	$result = $twtString;

	return $result;
}


function getTimeElapsedString($timestamp, $full = false) {
	$now = new DateTime;
	$ago = new DateTime;
	$ago->setTimestamp($timestamp);

	$agoText = 'ago';
	if ($now < $ago) {
		$agoText = 'in the future';
	}

	$diff = $now->diff($ago);

	//$diff->w = floor($diff->d / 7);
	$w = floor($diff->d / 7);
	$d = $diff->d - ($w * 7);
	//$diff->d -= $diff->w * 7;

	$string = array(
		'y' => 'year',
		'm' => 'month',
		'w' => 'week',
		'd' => 'day',
		'h' => 'hour',
		'i' => 'minute',
		's' => 'second',
	);
	foreach ($string as $k => &$v) { // k is key, and v is value... Obviously
		if ($k === 'w') {
			if ($w) {
				$v = $w . ' ' . $v . ($w > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		} else {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}
	}

	if (!$full) $string = array_slice($string, 0, 1);
	return $string ? implode(', ', $string) . " $agoText" : 'just now';
}

function getCachedFileContentsOrUpdate($fileURL, $cacheDurationSecs = 15) {
	# TODO: Process the Warning
	# Warning: file_get_contents(https://eapl.mx/twtxt.net):
	# failed to open stream: HTTP request failed! HTTP/1.1 404 Not Found in

	$cacheFilePath = getCachedFileName($fileURL);

	// Check if cache file exists and it's not expired
	if (file_exists($cacheFilePath) && (time() - filemtime($cacheFilePath)) < $cacheDurationSecs) {
		return file_get_contents($cacheFilePath);
	}

	// File doesn't exist in cache or has expired, so fetch and cache it
	$contents = file_get_contents($fileURL);
	file_put_contents($cacheFilePath, $contents);

	return $contents;
}

function getCachedFileContents($filePath) {
	$cacheFile = getCachedFileName($filePath);

	// Check if cache file exists and it's not expired
	if (file_exists($cacheFile)) {
		return file_get_contents($cacheFile);
	}

	return null;
}

function updateCachedFile($filePath) {
	$cacheFilePath = getCachedFileName($filePath);
	# TODO: Report down URLs and stop loading them after a few tries

	# Get the last modification time of the local file
	$lastModifiedTime = file_exists($cacheFilePath) ? filemtime($cacheFilePath) : false;
	$lastModifiedHeader = $lastModifiedTime ? gmdate('D, d M Y H:i:s', $lastModifiedTime) . ' GMT' : null;

	# echo "lastModifiedHeader: $lastModifiedHeader<br>\n";

	global $config;
	global $agentVersion;

	# TODO: Check this from the main page, not in this function
	if (!array_key_exists('public_txt_url', $config) || !array_key_exists('public_nick', $config)) {
		die("Check your config.ini file. 'public_txt_url' or 'public_nick' missing");
	}

	$url = $config['public_txt_url'];
	$nick = $config['public_nick'];

	if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
		die("Check your config.ini file. 'public_txt_url' not valid");
	}

	# TODO: Add a validation for the nickname. For example at least 1 character.

	$agentName = 'timeline';
	$userAgentHeader = "User-Agent: $agentName/$agentVersion (+$url; @$nick)\r\n";

	$header = $lastModifiedHeader ? "If-Modified-Since: $lastModifiedHeader\r\n" : '';
	$header .= $userAgentHeader;

	# Set up the HTTP context with the 'If-Modified-Since' header
	$options = [
		'http' => [
			'method' => 'GET',
			'header' => $header,
		]
	];

	$context = stream_context_create($options);

	$response = @file_get_contents($filePath, false, $context);

	# Check if HTTP headers are available, usually when the server is available
	if (!isset($http_response_header)) {
		# echo "Failed to fetch headers. No HTTP request was made.\n";
		return;
	}

	if ($http_response_header) {
		# var_dump($http_response_header);

		foreach ($http_response_header as $header) {
			# Look for the Last-Modified header
			if (preg_match('/^Last-Modified:\s*(.+)$/i', $header, $matches)) {
				$dateString = $matches[1]; // Extracted date
				# echo "Extracted Date: $dateString\n";

				// Convert to Unix timestamp
				$lastModifiedTimestamp = strtotime($dateString);
				if ($lastModifiedTimestamp > $lastModifiedTime) {
					# echo "Remote file is newer. Load it!<br>\n";
				} else {
					# echo "Not modified since last request. No update needed.<br>\n";
					return;
				}
			}
		}
	}

	# Save the content if it was successfully retrieved
	if ($response !== false) {
		file_put_contents($cacheFilePath, $response);
		#echo "File updated successfully.\n";
	}
}

function getTwtsFromTwtxtString($url) {
	$fileContent = getCachedFileContents($url);

	if (is_null($fileContent)) {
		return null;
	}
	$fileContent = mb_convert_encoding($fileContent, 'UTF-8');

	$fileLines = explode("\n", $fileContent);

	$twtxtData = new TwtxtFile();

	foreach ($fileLines as $currentLine) {
		// Remove empty lines
		if (empty($currentLine)) {
			continue;
		}

		if (str_starts_with($currentLine, '#')) {

			// Check if comments (starting with #) have some metadata
			if (!is_null(getSingleParameter('url', $currentLine))) {
				$currentURL = getSingleParameter('url', $currentLine);

				if (empty($twtxtData->URLs)) {
					$twtxtData->mainURL = $currentURL;
				}
				$twtxtData->URLs[] = $currentURL;
			}
			if (!is_null(getSingleParameter('nick', $currentLine))) {
				$twtxtData->nick = getSingleParameter('nick', $currentLine);
			}
			if (!is_null(getSingleParameter('avatar', $currentLine))) {
				$twtxtData->avatar = getSingleParameter('avatar', $currentLine);
			}
			if (!is_null(getSingleParameter('emoji', $currentLine))) {
				$twtxtData->emoji = getSingleParameter('emoji', $currentLine);
			}
			if (!is_null(getSingleParameter('lang', $currentLine))) {
				$twtxtData->lang = getSingleParameter('lang', $currentLine);
			}
			if (!is_null(getSingleParameter('description', $currentLine))) {
				$twtxtData->description = getSingleParameter('description', $currentLine);
				// TODO - FIX BUG: only takes first word!
			}
			if (!is_null(getSingleParameter('follow', $currentLine))) {
				$twtxtData->following[] = getSingleParameter('follow', $currentLine);
			}
		}

		// Clean up nick if set to something like `@soren@darch.dk` instead of just `soren`
		// mosty for (re)feeds from Mastodon etc.
		if (str_contains($twtxtData->nick, "@")) {
			$str = $twtxtData->nick;
			$str = ltrim($str, "@");
			$twtxtData->nick = explode("@", $str)[0]; // take the first [0] from splitting the nick at "@"
		}

		// Fallback for nick and url if not set in twtxt.txt
		// TODO: Use nick from local follow list as fallback?
		if ($twtxtData->nick === "") {
			$str = parse_url($url, PHP_URL_HOST);
			$str = str_replace("www.", "", $str);
			$str = explode(".", $str)[0]; // take the first [0] from splitting the host at "."
			$twtxtData->nick = $str;
		}
		if ($twtxtData->mainURL === "") {
			$twtxtData->mainURL = $url;
		}


		if (!str_starts_with($currentLine, '#')) {
			$explodedLine = explode("\t", $currentLine);
			if (count($explodedLine) >= 2) {
				$dateStr = $explodedLine[0];
				$twtContent = $explodedLine[1];

				$twtContent = replaceMentionsFromTwt($twtContent);

				// Convert HTML problematic characters
				//$twtContent = htmlentities($twtContent); // TODO: Messing up rendering of @mentions #BUG

				// Replace the Line separator character (U+2028)
				// \u2028 is \xE2 \x80 \xA8 in UTF-8
				// Check here: https://www.mclean.net.nz/ucf/
				//$twtContent = str_replace("\xE2\x80\xA8", "<br>\n", $twtContent);

				// For some reason I was having trouble finding this nomenclature
				// that's why I leave the UTF-8 representation for future reference
				//$twtContent = str_replace("\u{2028}", "\n<br>\n", $twtContent);
				$twtContent = str_replace("\u{2028}", "\n", $twtContent);

				$twtContent = embedYoutubeFromTwt($twtContent);

				// Get and remove the hash
				$hash = getReplyHashFromTwt($twtContent);
				if ($hash) {
					$twtContent = str_replace("(#$hash)", '', $twtContent);
				}

				// Interpret the content as markdown
				$Parsedown = new Parsedown();
				$twtContent = $Parsedown->text($twtContent);

				// TODO: Remove obserlete fuctions, or build our own simpler markdown parser?
				//$twtContent = replaceMarkdownLinksFromTwt($twtContent); 
				//$twtContent = replaceImagesFromTwt($twtContent);
				//$twtContent = replaceLinksFromTwt($twtContent);

				// TODO: Make ?tag= filtering feature
				$twtContent = replaceTagsFromTwt($twtContent);

				// TODO: Get mentions
				$mentions = getMentionsFromTwt($twtContent);

				// Get Lang metadata

				if (($timestamp = strtotime($dateStr)) === false) {
					//echo "The string ($dateStr) is incorrect";
					// Incorrect date string, skip this twt
					continue;
				} else {
					$displayDate = getTimeElapsedString($timestamp);
				}

				// TODO: Only 1 twt by second is allowed here
				$twt = new Twt();

				$twt->originalTwtStr = $currentLine;
				$twt->hash = getHashFromTwt($currentLine, $twtxtData->mainURL);
				$twt->timestamp = $timestamp;
				$twt->fullDate = date('j F Y h:i:s A', $timestamp) . ' (UTC)';
				$twt->displayDate = $displayDate;
				$twt->content = $twtContent;
				$twt->replyToHash = $hash;
				$twt->mentions = $mentions;
				$twt->avatar = $twtxtData->avatar;
				$twt->emoji = $twtxtData->emoji;
				$twt->nick = $twtxtData->nick;
				$twt->mainURL = $twtxtData->mainURL;

				$twtxtData->twts[$timestamp] = $twt;
			}
		}
	}

	return $twtxtData;
}

function insertFollowingURL($urlString) {
	// Check if it's a valid URL
	// Retrieve the nickname, if didn't find a nick, ask for one

	$originalCode = '
	Lorem ipsum dolor sit amet,
	#~~~#
	consectetur adipiscing elit.';

	$text = '#~~~#';
	$newText = '123' . PHP_EOL . $text;
	$result = str_replace('#~~~#', $newText, $originalCode);

	echo $result;
}

function getCachedFileName($filePath) {
	return __DIR__ . '/../private/cache/' . hash('sha256', $filePath); // TODO: make better path
}

if (!function_exists('str_starts_with')) {
	function str_starts_with($haystack, $needle) {
		return (string)$needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0;
	}
}
if (!function_exists('str_ends_with')) {
	function str_ends_with($haystack, $needle) {
		return $needle !== '' && substr($haystack, -strlen($needle)) === (string)$needle;
	}
}
if (!function_exists('str_contains')) {
	function str_contains($haystack, $needle) {
		return $needle !== '' && mb_strpos($haystack, $needle) !== false;
	}
}
