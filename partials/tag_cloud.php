<?php

// Tagcloud for twtxt
// Base on code from: https://social.dfaria.eu/search

// if(empty($_GET['search'])) {

    // Add all tags to one array
    foreach ($twts as $twt) {
        $tag_array = getTagsFromTwt($twt->content);

        foreach ($tag_array as $tag) {
          $tags[] = $tag[0];
        }   
    }

    natcasesort($tags);
    $tag_count = array_count_values($tags);
    //arsort($tag_count, SORT_STRING);
    //ksort($tag_count, SORT_STRING);
    //strnatcasecmp($tag_count);

    $max_count = max($tag_count);

    $min_font_size = 10;
    $max_font_size = 30;
    $num_intermediate_levels = 1;
    $font_size_interval = ($max_font_size - $min_font_size) / ($num_intermediate_levels + 1);

    $uri = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

    foreach ($tag_count as $tag => $count) {
        $font_size = $min_font_size + ($count / $max_count) * $font_size_interval;
        $tag = str_replace('#', '', $tag);
        echo '<a href="'.$uri.'&search='.$tag.'" style="font-size: '.$font_size.'px;">#'. $tag .'</a> ';
        //echo '<a href="?search='.$tag.'">#'. $tag .' ('.$count.')</a> ';
    }

    // Detail/summary with top tags and solo tags

    /*
    $top_tags = array_filter($tag_count, function($val){return ($val>1);});
    $solo_tags = array_diff($tag_count, $top_tags);
    krsort($solo_tags, SORT_STRING);

    echo "<details><summary> Tags: ";

    foreach ($top_tags as $tag => $count) {
        $tag = str_replace('#', '', $tag);
        echo '<a href="?search='.$tag.'">#'.$tag.'</a> ';
    }

    echo "</summary>";

    foreach ($solo_tags as $tag => $count) {
        $tag = str_replace('#', '', $tag);
        echo '<a href="?search='.$tag.'">#'.$tag.'</a> ';
    }

    echo "</details>";
    */

// } else {
//     echo "Showing posts with <code>".$_GET['search']."</code>";
// }