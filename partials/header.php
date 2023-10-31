<?php 

$profile = getTwtsFromTwtxtString($config['public_txt_url']);
$profile = getTwtsFromTwtxtString("http://darch.dk/twtxt.txt");

?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="/libs/simple.css">
    <link rel="stylesheet" type="text/css" href="/style.css">
    <title><?= $title ?></title>
</head>
<body>

<header>
    <p>
        <a href="/">
            <img class="avatar" src="<?= $profile->avatar ?>" alt="" loading="lazy">
            Timeline for <?= $profile->nick ?>@<?= parse_url($profile->mainURL, PHP_URL_HOST); ?>
        </a>

        <!-- <a href="/">🧶 Timeline for <?= $url ?> </a> -->
        <!-- for <a href="http://localhost:8000/?twts=http://darch.dk/twtxt.txt">sorenpeter</a>@darch.dk -->
        <!-- (<a href="http://localhost:8000/twtxt.txt">twtxt.txt</a>) -->
    </p> 
    <nav>
        <!-- TODO: make automatic via PHP and show avatar as well -->
        <ul class="secondary">
            <?php //if ($validSession) {  // TODO: Make login seqcure ?>
            <?php if( isset($_SESSION['password'])) { /*
                if($_SESSION['password']=="$password") {*/ // Hacky login ?>   
                <li><a href="/refresh?url=<?= $url ?>">Refresh</a></li>
                <li><a href="/following">Following <?php // echo count($twtFollowingList); ?></a></li>
                <!-- <li><a href="/new">New post</a></li> -->
                <li><a href="/add">Add feed</a></li>
                <!-- <li><a href="/admin">Settings</a></li> -->
                <li><a href="/logout">Log Out</a></li>
            <?php /*}*/ } else { ?>
                <li><a href="/login">Log in</a></li>
            <?php }  ?>
                <!-- <li><?php //include 'partials/listSelect.php'; ?></li> -->

        </ul>
    </nav>
</header>

<main>