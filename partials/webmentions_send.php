<?php
require_once "libs/twtxt.php";

// Send webmentions (TODO: move to it own file?)
$new_mentions = getMentionsFromTwt($twt);

foreach ($new_mentions as $mention) {
	//print_r(getMentionsFromTwt($twt));
	//echo $mention["nick"] . " from " . $mention["url"]."<br>";

	// Detect webmention endpoint define in twtxt.txt as `# webmention = URL`
	$targets_webmention_endpoint = getSingleParameter("webmention", file_get_contents($mention["url"]));

	if (!isset($targets_webmention_endpoint)) {
		echo "<p class='notice'>No endpoint found in: ".$mention["url"]."</p>";

	} else {

		$new_twt_url = $public_txt_url."#:~:text=".$datetime;
		//$target_url = $mention["url"];
		$payload = "source=".$new_twt_url."&target=".$mention["url"];
		//echo $payload;

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $targets_webmention_endpoint);
		curl_setopt($curl, CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
		$data = curl_exec($curl);
		$status = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
		curl_close($curl);

		echo "<p class='notice'>A webmention was send to: ".$targets_webmention_endpoint." (Status: $status)</p>";
	}
}