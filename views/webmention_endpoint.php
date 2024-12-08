<?php
# Source: https://gist.github.com/adactio/6484118
# Licensed under a CC0 1.0 Universal (CC0 1.0) Public Domain Dedication
# http://creativecommons.org/publicdomain/zero/1.0/

// Set path to your mentions twtxt file:
$logfile = './mentions.txt'; /* Make sure file is writeable */



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
$sourceFile = ob_get_contents();
ob_end_clean();


// Log if Webmention is not a twtxt-formated by chekcing for text fragment:
if(!stristr($_POST['source'], ":~:text=")) {

	$log  = date("Y-m-d\TH:i:s\Z") . "\t"
    ."Recived a non-twtxt webmention from ".$_POST['source']."  "
    ." mentioning ".$_POST['target']."  "
   	." (IP: ".$_SERVER['REMOTE_ADDR'].")"."  "
    .PHP_EOL;

 	file_put_contents($logfile, $log, FILE_APPEND);

 	exit;
}

// Process Webmentions with text fragment as to point to a twt in a twtxt.txt
if (stristr($sourceFile, $_POST['target'])) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 202 Accepted');

	# Now do something with $sourceFile e.g. parse it for h-entry and h-card and store what you find.

	// TODO: test if $url_time is to be found in $sourceFile
	//		 		and then write the $twt to the $log

	$url_time = explode( "#:~:text=", $_POST['source'] ); // split source into array with [0] for url and [1] for datetime
	//$url = preg_replace("(^https?://)", "", $url_time[0] );
	$pattern = '/^'.$url_time[1].'\t(.*?)$/m';
	preg_match($pattern, $sourceFile, $twt); // $twt[1] contains your line.

	$log  = date("Y-m-d\TH:i:s\Z") . "\t" 
		//.'You were mentioned in: <a href="'.$_POST['source'].',%0A" rel=noopener>'.$_POST['source'].'</a>' // "%0A" means new line
		.'Mention from '.$url_time[0].' in <a href="'.$url_time[0].'#:~:text='.urlencode($url_time[1]).'" rel="noopener" target="_blank">'.$url_time[1].'</a>'
		// TODO: find a way to make this as markdown and the render it with correct rel and target
		." " // add a twt line break before blockquote
		."> " . $twt[1]
    .PHP_EOL;
	 	file_put_contents($logfile, $log, FILE_APPEND);


# Send email fork: https://gist.github.com/otherjoel/9301d985622f0d3d1a09

}
else {
  header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
  exit;	
}

?>
