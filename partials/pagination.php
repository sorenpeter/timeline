<?php

$maxTwts = $config['twts_per_page'] ?? 50; // Fallback number if twts_per_page not set in config.ini

$totalTwts = count($twts);

if ( ($maxTwts >= $totalTwts) || ($paginateTwts === false) ) {
	// echo "Only " . $totalTwts . " so no need for pagnation. (max: ". $maxTwts . ")<br>";
	$paginateTwts = false;

} else {
	// echo "Over " . $maxTwts . " so pagnation is needed.<br>";
	$paginateTwts = true; // for showing pagnation navigation below timeline

	$totalPages = ceil($totalTwts / $maxTwts);

	$currentPage = max(1, min($_GET["page"], $totalPages));

	// Split up twts into pages
	$startingTwt = (($currentPage - 1) * $maxTwts);
	$twts = array_slice($twts, $startingTwt, $maxTwts);

	// Figure out base URL for prev/next links
	$pageURL = $_SERVER['REQUEST_URI'];
	//echo $pageURL."<hr>";

	if (!empty($_GET['page'])) {

		if (preg_match('/\?page=/', $pageURL)) {
			//echo "cotains ?page= so reuse that <hr>";
			$pageURL = preg_replace('/\?page=\d*/', "", $pageURL) . '?page=';
		}

		if (preg_match('/&page=/', $pageURL)) {
			//echo "cotains &page= so reuse that <hr>";
			$pageURL = preg_replace('/&page=\d*/', "", $pageURL) . '&page=';
		}

	} else {

		if (!preg_match('/(\?|&)/', $pageURL)) {
			//echo "No param, so use ?page <hr>";
			$pageURL = $pageURL . '?page=';
		}
		
		if (!preg_match('/(\?|&)page/', $pageURL)) {
			//echo "other param than _page, so use &page <hr>";
			$pageURL = $pageURL . '&page=';
		}	
	}

}
