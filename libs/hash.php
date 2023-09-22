<?php
declare(strict_types=1);
date_default_timezone_set('UTC');

require_once('libs/Base32.php');
# twtxt Hash extension
# https://dev.twtxt.net/doc/twthashextension.html

function getHashFromTwt(string $twt, string $url): string {
	$explodedLine = explode("\t", $twt);
	//print_r($explodedLine);

	if (count($explodedLine) >= 2) {
		$dateStr = $explodedLine[0];
		$twtContent = $explodedLine[1];

		// dateStrings without timezone should be assumed as UTC
		$dt = new DateTime($dateStr);

		// Getting the new formatted datetime
		//$dateStr = $dt->format(DateTime::ATOM); // Updated ISO8601
		$dateStr = $dt->format(DateTime::RFC3339);
		$dateStr = str_replace('+00:00', 'Z', $dateStr);
		$dateStr = str_replace('-00:00', 'Z', $dateStr);

		$hashPayload = "$url\n$dateStr\n$twtContent";

		// Default to 32 bytes
		// https://www.php.net/manual/en/function.sodium-crypto-generichash.php
		$hashBytes = sodium_crypto_generichash($hashPayload);
		$hashStr = substr(Base32::encode($hashBytes), -7);

		return $hashStr;
	}

	return 'INVALID';
}

function checkValidHashes() {
	$url = 'http://magical.fish:70/feeds/twtxt/twtxt.txt';

	$twt = "2023-06-17T00:33:32-06:00\tSun's out funs out!";
	$expectedHash = 'ujcbz3q';

	assert(getHashFromTwt($twt, $url) === $expectedHash);

	$twt = "2023-06-20T07:51:48-06:00\tWhat a way to go.";
	$expectedHash = 'f7hzthq';
	assert(getHashFromTwt($twt, $url) === $expectedHash);

	echo 'Asserts passed';
}