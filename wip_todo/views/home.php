<?php require_once("partials/base.php"); ?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="../style.css">
    <title><?=$title?> - Timeline</title>
</head>
<body >

<nav class="edit">
    <?php
    // $config = parse_ini_file('private/config.ini');
    // $password = $config['password'];

    if($_SESSION['password']=="$password")
    {
        ?>
        <small>You are loggged in</small>

        <form method="post" action="" id="logout_form">
          <input type="submit" name="page_logout" value="Log out" class="right">
        </form>  
    <?php
        } else {
            ?>
            <small><a href="/login">Log in</a></small>
    <?php } ?>
</nav>

<!-- PHP: GET HEADER  --><?php include 'partials/header.php';?>

<!-- PHP: GET PROFILE CARD  --><?php include 'partials/profile.php';?>

<main class="timeline">

<center><h3>
    <?php if (!empty($_GET['twts'])) { ?>
        <em>Twts for <a href="<?= $twtsURL ?>"><?= $twtsURL ?></a></em>
    <?php } else { ?>
        <em>Timeline for <a href="<?= $url ?>"><?= $url ?></a></em>
    <?php } ?>
</h3></center>

<!-- PHP: GET TIMELIE  --><?php include 'partials/timeline.php'?>

</main>

<!-- PHP: GET FOOTER  --><?php include 'partials/footer.php';?>

</body>
</html> 
