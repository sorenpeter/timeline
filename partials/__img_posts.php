<?php

/*
    Gallery render of images posted to a twtxt.txt -- by darch.dk (2022)
    https://www.reddit.com/r/css/comments/8vmo4u/problem_fitting_image_to_css_grid/
*/

echo '<div class="gallery">';

foreach ($twts as $twt) {
	$img_array = getImagesFromTwt($twt->content);

	foreach ($img_array as $img) {
		//echo '<a href="'.$baseURL.'/post/'.$twt->hash.'">'.$img[0].'</a>';
		// Workaround until cache issue is resolved 
		echo '<a href="'.$baseURL.'/?profile='.$twt->mainURL.'#'.$twt->hash.'">'.$img[0].'</a>';
	}
}

echo '</div>';


/*

// old way from Pixelblog, for refernece/inspiration 

// Loop through each post and extract date and entry text:

foreach ($img_posts as $post) {

	$date = preg_filter('/^(?<date>[^\t]+)\t(?<entry>.+)/', '\1', $post);
	$entry = preg_filter('/^(?<date>[^\t]+)\t(?<entry>.+)/', '\2', $post);
	$text_only = preg_filter('/!\[(.*?)\]\((.*?)\)/', '\1', $entry); // this gives the post without the markdown img links (not sure why, but it works)
	$text_only = trim($text_only);
	$text_only = strip_tags($text_only);

	preg_match_all('/!\[(?<alt>.*?)\]\((?<url>.*?)\)/', $entry, $img_array);
	//echo '<pre>'; print_r($img_array);    echo '</pre>';      // FOR DEBUGING

	foreach ($img_array['url'] as $img => $val) {
		$url = $img_array['url'][$img];
		//$alt = $img_array['alt'][$img];
		echo '<a href="'.$baseURL.'/?id='.$date.'"><img src="'.$url.'" alt="'.$text_only.'" title="'.$text_only.'" loading=lazy></a>';
		//echo '<a href="' . $base_url . '?id=' . $date . '"><img src="' . $base_url . 'system/thumb.php?src=' . $url . '&size=600x&crop=1" alt="' . $text_only . '" title="' . $text_only . '" loading=lazy></a>';

	}
}

*/

?>
