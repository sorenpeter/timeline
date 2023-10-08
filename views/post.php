<?php require_once("partials/base.php"); 

// Get the hash (only post, not replies) as $id from the router
if (!empty($id)) {
    $twts = array_filter($twts, function($twt) use ($id) {
        return $twt->hash === $id;
    });
}

?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="/style.css">
    <title>Post: <?= $id ?></title>
</head>
<body >

<!-- PHP: GET HEADER  --><?php include 'partials/header.php';?>

<main>

<h2>Post: #<?= $id ?></h2>

<!-- PHP: GET TIMELIE  --><?php include 'partials/timeline.php'?>

</main>

<!-- PHP: GET FOOTER  --><?php include 'partials/footer.php';?>

</body>
</html> 