<?php

/*

TODO:
+ Test if $twts are bigger/lowe than max to detemen if pagnations is needed
+ Make pagnation work with profile views
- Move twts per page to config.ini
	- Add a fallback if the number is invalid (it should be between 1 and 999)
	//$twts_per_page = $config['twts_per_page'];
	
*/

const TWTS_PER_PAGE = 20;
$twts_per_page = TWTS_PER_PAGE;

$totalTwts = count($twts);

// Test if pagnation is needed and set stat
// TODO: consider how to have /conv alway show all twts

if ( $twts_per_page >= $totalTwts ) {
	//echo "Only " . $totalTwts . " so no need for pagnation. (max: ". $twts_per_page . ")<br>";
	$paginateTwts = false;
} else {
	//echo "Over " . $twts_per_page . " so pagnation is needed.<br>";
	$paginateTwts = true;
}

$page = 1;
if (!empty($_GET['page'])) {
	$page = intval($_GET['page']);
}

// If we should paginate our twts list
if (!empty($paginateTwts)) {
	$startingTwt = (($page - 1) * TWTS_PER_PAGE);
	$twts = array_slice($twts, $startingTwt, TWTS_PER_PAGE);
}

# Approach 2: Ignore invalid page numbers, and adjust the number between 1 and the last page
$currentPage = max(1, min($_GET["page"], $totalPages));

# And then in the HTML template only show the next page link if we are NOT on the last one
if ($currentPage < $totalPages) {
  $output .= '<a href="?page=' . ($currentPage + 1) . '">&gt; Next Page</a>';
}

$totalTwts = count($twts);
//echo "pagnation twts: " . $totalTwts;

$totalPages = ceil($totalTwts / $twts_per_page);
//echo "<br>total pages: " . $totalPages;

$page_url = $_SERVER['REQUEST_URI'];
//echo $page_url."<hr>";

if (!empty($_GET['page'])) {

	if (preg_match('/\?page=/', $page_url)) {
		//echo "cotains ?page= so reuse that <hr>";
		$page_url = preg_replace('/\?page=\d*/', "", $page_url) . '?page=';
	}

	if (preg_match('/&page=/', $page_url)) {
		//echo "cotains &page= so reuse that <hr>";
		$page_url = preg_replace('/&page=\d*/', "", $page_url) . '&page=';
	}

} else {

	if (!preg_match('/(\?|&)/', $page_url)) {
		//echo "No param, so use ?page <hr>";
		$page_url = $page_url . '?page=';
	}
	
	if (!preg_match('/(\?|&)page/', $page_url)) {
		//echo "other param than _page, so use &page <hr>";
		$page_url = $page_url . '&page=';
	}	
}

?>
