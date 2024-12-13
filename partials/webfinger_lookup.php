<?php

function splitNickDomain($str) {
	$str = trim($str);
	$str = str_replace("acct:", "", $str); // remove "acct:" prefix
	$str = ltrim($str, "@"); // remove leading "@"
	if (filter_var($str, FILTER_VALIDATE_EMAIL) ) {
		return array("nick" => explode("@",$str)[0], "domain" => explode("@",$str)[1] );
	} else {
		return FALSE;
	}
}

$wf_acct = "";
$wf_nick = "";
$wf_url = "";
$wf_error = "";
$wf_request = "";

if(isset($_POST['submit'])) { 
	$wf_request = $_POST["webfinger"];

	$wf_acct = splitNickDomain($wf_request);
	if($wf_acct === FALSE) { $wf_error = "ERROR: ".$wf_request." does not look like a valid webfinger handle"; }

	$wf_json = file_get_contents("https://".$wf_acct["domain"]."/.well-known/webfinger?resource=acct:".$wf_acct["nick"]."@".$wf_acct["domain"]);
	//if($wf_json === FALSE) { $wf_error = "ERROR: Chould not find a twtxt feed at webfinger endpoint ".$wf_request; }

	$wf_array = json_decode($wf_json, true);

	$wf_domain = splitNickDomain($wf_array["subject"])["domain"];

	foreach( $wf_array["links"] as $link ) {

		if ( $link["rel"] == "self" AND $link["type"] == "text/plain") {
			
			if ( filter_var($link["href"], FILTER_VALIDATE_URL) ) {
				$wf_url = 	$link["href"];

				$wf_nick = splitNickDomain($wf_array["subject"])["nick"];
				$wf_error = "URL to twtxt.txt found for ".$wf_nick ."@".$wf_domain;
				break;
			}
		} else { $wf_error = "ERROR: Chould not find a twtxt feed at webfinger endpoint ".$wf_acct["domain"]; }
	}
} 


//else { $wf_error = "Chould not find webfinger endpoint at ".$wf_acct["domain"]; }
