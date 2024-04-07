<?php
require_once("partials/base.php");

$title = "Replies - ".$title;

include_once 'partials/header.php';
?>


<!-- PHP: PROFILE CARD -->
<?php
if (!empty($_GET['profile'])) { // Show twts for some user
    $twtsURL = $_GET['profile'];

    // TODO: Give a propper error if feed is not valid
    if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
        die('Not a valid URL');
    }

    // $parsedTwtxtFile = getTwtsFromTwtxtString($twtsURL);
    if (!is_null($parsedTwtxtFile)) {
        $parsedTwtxtFiles[$parsedTwtxtFile->mainURL] = $parsedTwtxtFile;
        include 'partials/profile.php';
    }
} else {
    // TODO: default to rendering the local users gallery, if no profile specified
    //echo $profile->mainURL;;
    //$twtsURL = $profile->mainURL; // correct URL for twtxt.txt
}

?>


<!-- PHP: TIMELINE --><?php include_once 'partials/timeline.php'?>


<!-- PHP: FOOTER  --><?php include_once 'partials/footer.php';?>