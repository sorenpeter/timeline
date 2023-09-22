<?php

/* 
    Gallery render of images posted to a twtxt.txt -- by darch.dk (2022)
    https://www.reddit.com/r/css/comments/8vmo4u/problem_fitting_image_to_css_grid/
*/

// Load twtxt.txt file
//$filecontent = file('../../twtxt.txt');
$filecontent = file($twtxt);

// Only posts containing images
$img_pattern = '/!\[(.*?)\]\((.*?)\)/'; // Look for posts with images in markdown tags: ![alt](url)
$img_posts = preg_grep($img_pattern, $filecontent);
rsort($img_posts);
//echo 'img_posts: '.count($img_posts).'<hr>';                  // FOR DEBUGING
//echo '<hr><pre>'; print_r($img_posts);    echo '</pre>';      // FOR DEBUGING

// Loop through each post and extract date and entry text:
foreach($img_posts as $post){ 

    $date = preg_filter('/^(?<date>[^\t]+)\t(?<entry>.+)/', '\1', $post) ;
    $entry = preg_filter('/^(?<date>[^\t]+)\t(?<entry>.+)/', '\2', $post) ;
    $text_only = preg_filter('/!\[(.*?)\]\((.*?)\)/', '\1', $entry); // this gives the post without the markdown img links (not sure why, but it works)
    $text_only = trim($text_only);
    $text_only = strip_tags($text_only);

    preg_match_all('/!\[(?<alt>.*?)\]\((?<url>.*?)\)/', $entry, $img_array);
    //echo '<pre>'; print_r($img_array);    echo '</pre>';      // FOR DEBUGING

    foreach($img_array[url] as $img => $val) {
        $url = $img_array[url][$img];
        //$alt = $img_array[alt][$img];
        //echo '<a href="https://example.com/pixelblog/?id='.$date.'"><img src="'.$url.'" alt="'.$text_only.'" title="'.$text_only.'" loading=lazy></a>';
        echo '<a href="'.$base_url.'?id='.$date.'"><img src="'.$base_url.'system/thumb.php?src='.$url.'&size=600x&crop=1" alt="'.$text_only.'" title="'.$text_only.'" loading=lazy></a>';

    }
}

?>
