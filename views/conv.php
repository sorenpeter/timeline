<?php
$paginateTwts = false;
require_once("partials/base.php");

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

<center>
    <h2>Conversation</h2>
    <p>Recent posts in reply to <a href="<?= $baseURL ?>/post/<?= $id ?>">#<?= $id ?></a></p>
</center>

<!-- PHP: GET TIMELINE  --><?php include_once 'partials/timeline.php'?>


<?php
require_once 'libs/session.php';

if (hasValidSession()) {
    $textareaValue = "(#$id) ";
    include 'views/new_twt.php';
}
?>

<!-- PHP: GET FOOTER  --><?php include_once 'partials/footer.php';?>