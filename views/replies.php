<?php

//$paginateTwts = true;

if (!empty($_GET['url'])) { // Show twts for some user (Profile view)
    $twtsURL = $_GET['url'];
} else {
    $config = parse_ini_file('private/config.ini');
    $twtsURL = $config['public_txt_url'];
}

require_once("partials/base.php");

$title = "Replies by ".$title;

include_once 'partials/header.php';

//echo $twtsURL."bob";

include_once 'partials/profile_card.php';

if( isset($_SESSION['password'])) {
    include 'views/new_twt.php'; // TODO: Split up new_twt into a view and a partial
} 

//include_once 'partials/search.php';

include_once 'partials/timeline.php';

include_once 'partials/footer.php';

/*

// Old replies // 

<?php
require_once("partials/base.php");

$title = "Replies - ".$title;

include_once 'partials/header.php';
?>


<!-- PHP: PROFILE CARD -->
<?php
if (!empty($_GET['url'])) { // Show twts for some user
    $twtsURL = $_GET['url'];

    // TODO: Give a propper error if feed is not valid
    if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
        die('Not a valid URL');
    }

    // $parsedTwtxtFile = getTwtsFromTwtxtString($twtsURL);
    if (!is_null($parsedTwtxtFile)) {
        $parsedTwtxtFiles[$parsedTwtxtFile->mainURL] = $parsedTwtxtFile;
        include 'partials/profile_card.php';
    }
} else {
    // TODO: default to rendering the local users gallery, if no profile specified
    //echo $profile->mainURL;;
    //$twtsURL = $profile->mainURL; // correct URL for twtxt.txt
}

?>


<!-- PHP: TIMELINE --><?php include_once 'partials/timeline.php'?>


<!-- PHP: FOOTER  --><?php include_once 'partials/footer.php';?>
*/
