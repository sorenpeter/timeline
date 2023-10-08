<?php require_once("partials/base.php"); 

// Get the hashes (both post and replies) as $id from the router and return an inverted list
if (!empty($id)) {
    $twts = array_filter($twts, function($twt) use ($id) {
        return $twt->hash === $id || $twt->replyToHash === $id;
    });
    $twts = array_reverse($twts, true);
}

// && !preg_match('/conv/', $request)

?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="/style.css">
    <title>Conversation: <?= $id ?></title>
</head>
<body >



<!-- PHP: GET HEADER  --><?php include 'partials/header.php';?>

<main>

<h2>Conversation</h2>

<p>Recent twts in reply to <a href="/post/<?= $id ?>">#<?= $id ?></a></p>

<!-- PHP: GET TIMELIE  --><?php include 'partials/timeline.php'?>

</main>

<!-- PHP: GET FOOTER  --><?php include 'partials/footer.php';?>

</body>
</html> 