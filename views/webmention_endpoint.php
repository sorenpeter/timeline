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

$logfile = './mentions.txt'; /* Make sure file is writeable */

$log  = date("Y-m-d\TH:i:s\Z") . "\t" 
    ."Recived webmention from ".$_POST['source']
    ." mentioning ".$_POST['target']
   	." (IP: ".$_SERVER['REMOTE_ADDR'].")".PHP_EOL;
 	file_put_contents($logfile, $log, FILE_APPEND);

}
else {
  header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
  exit;	
}

?>
