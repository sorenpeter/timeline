<?php

// Search / filter on tags (or anything within a twt actually)
// Base on hash filter below and on code from: https://social.dfaria.eu/search

if (!empty($_GET['search'])) {
    $search = $_GET['search'];

    $pattern = preg_quote($search, '/');
    $pattern = "/^.*$pattern.*\$/mi";

    // TODO: Make into a genral function in twtxt.php since i use it so much in profile.php and potentialle other places
    $twts = array_filter($twts, function ($twt) use ($pattern) {
        return preg_match($pattern, $twt->content);
    });
}
