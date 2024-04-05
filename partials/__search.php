<?php

// Search / filter on tags (or anything within a twt actually)
// Base on code from: https://social.dfaria.eu/search

if (!empty($_GET['search'])) {

    $searchfor = $_GET['search'];
    //$file = 'twtxt.txt';
    //$contents = file_get_contents($url);

    $pattern = preg_quote($searchfor, '/');
    $pattern = "/^.*$pattern.*\$/mi";

    // 1. filter $twts for maches containg search string
    /*
    $twts_filtered = array_filter($twts, function($twt) {
        return preg_match($pattern, $twt->content);
    });

    echo "<pre>";
    print_r($twts[1711985096]);
    */


    $twts_filtered = [];

    //print_r($twts_filtered);

    // 2. Repalce original $twts with new $twts_filtered
    //$twts = $twts_filtered


    foreach ($twts as $twt) {

        if (preg_match_all($pattern, $twt->content, $matches)) {
            
            echo "<hr>";
            print_r($twt);
            


            /*
            $date = preg_filter('/^(?<date>[^\t]+)\t(?<entry>.+)/', '\2', $matches[0]);
            $entry = preg_filter('/^(?<date>[^\t]+)\t(?<entry>.+)/', '\1', $matches[0]);

            foreach ($date as $i => $tw) {
                $post[$tw] = $entry[$i];
            }

            $post = array_reverse($post);
            $perpage = 10;

            if(isset($_GET['start'])) $start = $_GET['start']; else $start = 0;
            
            $numposts = count($post);
            $post = array_slice($post, $start, $perpage);
            
            echo "<hr>";
            foreach ($post as $tw => $data) {
                echo $tw;
                echo "<hr>";
            }
            */
        }
    }
}