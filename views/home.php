<?php
require_once("partials/base.php");

//$title = "Login - ".$title;

include 'partials/header.php';
?>

<!-- PHP: PROFILE CARD -->
<?php

/*
if (!empty($_GET['twts'])) { // Show profile for some user
    $twtsURL = $_GET['twts'];

    // TODO: Give a propper error if feed is not valid
    if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
        die('Not a valid URL');
    }

    // $parsedTwtxtFile = getTwtsFromTwtxtString($twtsURL);
    if (!is_null($parsedTwtxtFile)) {
        $parsedTwtxtFiles[$parsedTwtxtFile->mainURL] = $parsedTwtxtFile;
        include 'partials/profile.php';
    }
}
*/

?>

<!-- PHP: NEW POST BOX -->
<?php
if (isset($_SESSION['password'])) { 
    include 'views/new_twt.php'; // TODO: Split up new_twt into a view and a partial
}

/*
else {
    $url = "Location: /?twts=".$config['public_txt_url'];
    //header($url);
}
*/

?>

<!-- PHP: TIMELINE --><?php include 'partials/timeline.php'?>

<!-- PHP: FOOTER  --><?php include 'partials/footer.php';?>

