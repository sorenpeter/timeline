<?php
# Source: https://gist.github.com/adactio/6484118
# Licensed under a CC0 1.0 Universal (CC0 1.0) Public Domain Dedication
# http://creativecommons.org/publicdomain/zero/1.0/

if (!isset($_POST['source']) || !isset($_POST['target'])) {
  print('Please send a propper webmention to this endpoint');
  header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
  exit;
}

ob_start();
$ch = curl_init($_POST['source']);
curl_setopt($ch,CURLOPT_USERAGENT,'Timeline Twtxt Web-client (webmention.org)');
curl_setopt($ch,CURLOPT_HEADER,0);
$ok = curl_exec($ch);
curl_close($ch);
$source = ob_get_contents();
ob_end_clean();

function twtFromDate($url) {
	// Split URL into fragment and file path, and retrurns twt maching date
	$datetime = parse_url($url, PHP_URL_FRAGMENT);
	$twtfile = strtok($url, "#");
	return preg_grep($datetime, explode(PHP_EOL,$source));	
}

function twtMentionInSource($twt){
	// Tests if twt contains a mentions to target
	$pattern = '/@<([^>]+)\s([^>]+)>/'; // Matches "@<nick url>"
	// Match only mention of specific URL from TARGET
	return preg_match($pattern, $twt);
}

if (stristr($_POST['source'], ".txt#")) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 202 Accepted');
	
	// Split URL into fragment and file path, and retrurns twt maching date
	$datetime = "/".parse_url($_POST['source'], PHP_URL_FRAGMENT)."/";
	//$twtfile = strtok($_POST['source'], "#");
	$txt = explode(PHP_EOL, $source);
	$twt = preg_grep($datetime, $txt);	


	/*
	if (twtMentionInSource($twt) {
		$twtMention = "YES!";
	}
	*/

	$logfile = './mentions.txt'; // Make sure file is writeable

	$log  = date("Y-m-d\TH:i:s\Z") . "\t" 
	    ."Recived webmention from ".$_POST['source']
	    ." mentioning ".$_POST['target']
	    ." -- ".$twt.$datetime
	   	//." (IP: ".$_SERVER['REMOTE_ADDR'].")"
	   	.PHP_EOL;
	 	file_put_contents($logfile, $log, FILE_APPEND);

}
else {
	header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
	exit;	
}

?>
