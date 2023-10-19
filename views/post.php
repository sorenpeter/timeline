<?php require_once("partials/base.php"); 

// Get the hash (only post, not replies) as $id from the router
if (!empty($id)) {
    $twts = array_filter($twts, function($twt) use ($id) {
        return $twt->hash === $id;
    });
}

$title = "Post: ".$id." - ".$title;

include 'partials/header.php';
?>

<h2>Post: #<?= $id ?></h2>

<!-- PHP: GET TIMELIE  --><?php include 'partials/timeline.php'?>

<!-- PHP: GET FOOTER  --><?php include 'partials/footer.php';?>