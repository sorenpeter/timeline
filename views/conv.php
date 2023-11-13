<?php require_once("partials/base.php"); 

// Get the hashes (both post and replies) as $id from the router and return an inverted list
if (!empty($id)) {
    $twts = array_filter($twts, function($twt) use ($id) {
        return $twt->hash === $id || $twt->replyToHash === $id;
    });
    $twts = array_reverse($twts, true);
}

// && !preg_match('/conv/', $request)

$title = "Conversation: ".$id." - ".$title;

include_once 'partials/header.php';
?>

<h2>Conversation</h2>

<p>Recent twts in reply to <a href="<?= $baseURL ?>/post/<?= $id ?>">#<?= $id ?></a></p>

<!-- PHP: GET TIMELIE  --><?php include_once 'partials/timeline.php'?>

<?php
if (isset($_SESSION['password'])) {
    $textareaValue = "(#$id) ";
    include 'views/new_twt.php';
} ?>

<!-- PHP: GET FOOTER  --><?php include_once 'partials/footer.php';?>