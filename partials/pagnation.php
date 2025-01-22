<?php

/*

TODO: Make pagnation work with profile views

*/

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

<div class="pagnation">
	<?php if ($page > 1) { ?>
		<a href="<?= $page_url . $page-1 ?>"><i class="fa fa-chevron-left" aria-hidden="true"></i> Previous</a>
	<?php } else { ?>
		<span style="color: var(--disabled);"><i class="fa fa-chevron-left" aria-hidden="true"></i> Previous</span>
	<?php } ?>
	<strong>&nbsp;<?= $page ?>&nbsp;</strong>
	<a href="<?= $page_url . $page+1 ?>">Next <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
</div>
