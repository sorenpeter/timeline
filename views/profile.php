<?php

if (!empty($_GET['url'])) { // Show twts for some user (Profile view)
    $twtsURL = $_GET['url'];
} else {

    // temp to get default url
    $config = parse_ini_file('private/config.ini'); 

    $twtsURL = $config['public_txt_url'];
}

require_once("partials/base.php");

$title = "Posts by ".$title;

include_once 'partials/header.php';

//echo $twtsURL."bob";

include_once 'partials/profile_card.php';

if( isset($_SESSION['password'])) {
    include 'views/new_twt.php'; // TODO: Split up new_twt into a view and a partial
} 

//include_once 'partials/search.php';

include_once 'partials/timeline.php';

include_once 'partials/footer.php';
