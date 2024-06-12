<?php
// Only paginate if it's a main timeline view
$paginateTwts = true;

/*
if (!empty($_GET['profile'])) { // Show twts for some user (Profile view)
    $twtsURL = $_GET['profile'];

    // TODO: Give a proper error if URL is not valid
    if (filter_var($twtsURL, FILTER_VALIDATE_URL) === FALSE) {
        die('Not a valid URL');
    }

    // If it's a profile, don't paginate
    $paginateTwts = false;
}
*/

// Load twts, taking $paginateTwts into consideration
require_once 'partials/base.php';

$title = "Timeline for ".$title;


// Redirect guests to Profile view, if url not set til home twtxt.txt

if (!isset($_SESSION['password']) && ($_GET['url'] != $config['public_txt_url']) ) {
    header('Location: ./profile');
    exit();
}

include_once 'partials/header.php';

if (isset($_SESSION['password'])) {
    include 'views/new_twt.php'; // TODO: Split up new_twt into a view and a partial
} else {
    
    echo '<center><h2>Timeline</h2>';

    echo '<p>Recent posts from feeds followed by <a href="./profile">
    <strong>'.$config['public_nick'].'</strong>@'. parse_url($profile->mainURL, PHP_URL_HOST).'</a></p></center>';
}

include_once 'partials/timeline.php';

include_once 'partials/footer.php';