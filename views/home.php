<?php
require_once("partials/base.php");

//$title = "Login - ".$title;

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
} ?>


<!-- PHP: NEW POST BOX -->
<?php
if( isset($_SESSION['password'])) { 
    include 'views/new_twt.php'; // TODO: Split up new_twt into a view and a partial
}
?>

<!-- PHP: TIMELINE --><?php include_once 'partials/timeline.php'?>

<!-- PHP: FOOTER  --><?php include_once 'partials/footer.php';?>

