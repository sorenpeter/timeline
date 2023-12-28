<?php
// Only paginate if it's a main timeline view
$paginateTwts = true;

if (!empty($_GET['profile'])) { // Show twts for some user (Profile view)
    $twtsURL = $_GET['profile'];

    // TODO: Give a proper error if URL is not valid
    if (filter_var($twtsURL, FILTER_VALIDATE_URL) === FALSE) {
        die('Not a valid URL');
    }

    // If it's a profile, don't paginate
    $paginateTwts = false;
}

// Load twts, taking $paginateTwts into consideration
require_once 'partials/base.php';
include_once 'partials/header.php';
?>

<!-- PHP: PROFILE CARD -->
<?php
if (!empty($_GET['profile'])) { // Show twts for some user (Profile view)
    $twtsURL = $_GET['profile'];

    if (!is_null($parsedTwtxtFile)) {
        $parsedTwtxtFiles[$parsedTwtxtFile->mainURL] = $parsedTwtxtFile;
        include 'partials/profile.php';
    }
}
?>

<!-- PHP: NEW POST BOX -->
<?php
if (isset($_SESSION['password'])) {
    include 'views/new_twt.php'; // TODO: Split up new_twt into a view and a partial
}
?>

<!-- PHP: TIMELINE --><?php include_once 'partials/timeline.php'?>

<!-- PHP: FOOTER  --><?php include_once 'partials/footer.php';?>
