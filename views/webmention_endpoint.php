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

if (stristr($source, $_POST['target'])) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 202 Accepted');

	# Now do something with $source e.g. parse it for h-entry and h-card and store what you find.

	// TODO: test if $datetime is to be found in $source
	//		 		and then write the $twt to the $log

	$datetime = explode( ":~:text=", $_POST['source'] );
	$pattern = '/^'.$datetime[1].'\t(.*?)$/m';
	preg_match($pattern, $source, $twt); // $twt[1] contains your line.

	//preg_match('/^'.$datetime[1].'\t(.*?)$/m', $source, $twt);

	$logfile = './mentions.txt'; /* Make sure file is writeable */

	$log  = date("Y-m-d\TH:i:s\Z") . "\t" 
		.'You were mentioned in: <a href="'.$_POST['source'].',%0A" rel=noopener>'.$_POST['source'].'</a>' // "%0A" means new line
		." " // add a line break before blockquote
		."> " . $twt[1]
	    //."Recived webmention from ".$_POST['source']
	    //." mentioning ".$_POST['target']
	   	//." (IP: ".$_SERVER['REMOTE_ADDR'].")"
	    .PHP_EOL;
	 	file_put_contents($logfile, $log, FILE_APPEND);


# Send email fork: https://gist.github.com/otherjoel/9301d985622f0d3d1a09

}
else {
  header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
  exit;	
}

?>
