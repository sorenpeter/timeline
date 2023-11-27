<?php 

$profile = getTwtsFromTwtxtString($config['public_txt_url']);

?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="webmention" href="<?= $baseURL ?>/webmention" />
    <link rel="stylesheet" href="<?= $baseURL ?>/libs/simple.css">
    <link rel="stylesheet" type="text/css" href="<?= $baseURL ?>/style.css">
    <title><?= $title ?></title>
</head>
<body>

<header>
    <p>
        <a href="<?= $baseURL ?>">
            <img class="avatar" src="<?= $profile->avatar ?>" alt="" loading="lazy">
            <!-- Timeline for --><?= $profile->nick ?></a>@<?= parse_url($profile->mainURL, PHP_URL_HOST); ?>
    </p> 
    <nav>

        <ul class="secondary">
            <?php //if ($validSession) {  // TODO: Make login seqcure ?>
            <?php if( isset($_SESSION['password'])) { /*
                if($_SESSION['password']=="$password") {*/ // Hacky login ?>   
                <li><?php include 'partials/lists.php'; ?></li>
                <li><a href="<?= $baseURL ?>/refresh?url=<?= $url ?>">Refresh</a></li>
                <li><a href="<?= $baseURL ?>/following">Following <?php // echo count($twtFollowingList); ?></a></li>
                <li><a href="<?= $baseURL ?>/add">Add feed</a></li>
                <li><a href="<?= $baseURL ?>/?profile=<?=$url ?>">Profile</a></li>                
                <li><a href="<?= $baseURL ?>/logout">Log Out</a></li>
            <?php /*}*/ } else { ?>
                <li><a href="<?= $baseURL ?>/following">Following <?php // echo count($twtFollowingList); ?></a></li>
                <li><a href="<?= $baseURL ?>/?profile=<?= $url ?>">Profile</a></li>                
                <li><a href="<?= $baseURL ?>/login">Log in</a></li>
            <?php }  ?>
        </ul>
    </nav>
</header>

<main>


