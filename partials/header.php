<?php 

$profile = getTwtsFromTwtxtString($config['public_txt_url']);

?>

<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" type="image/x-icon" href="<?= $baseURL ?>/media/logo.png">
    <link rel="stylesheet" type="text/css" href="<?= $baseURL ?>/libs/simple.css">
    <link rel="stylesheet" type="text/css" href="<?= $baseURL ?>/libs/timeline.css">
    <link rel="stylesheet" type="text/css" href="<?= $baseURL ?>/custom.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="webmention" href="<?= $baseURL ?>/webmention" />
    <title><?= $title ?></title>
</head>
<body>

<header>
    <p>
        <a href="/">
            <img class="logo" src="<?= $baseURL ?>/media/logo.png">
            <?= $config['site_title']; ?>
        </a>
<!--        <a href="/">
            <img class="logo" src="<?= $profile->avatar ?>" alt="" loading="lazy">
            <?= parse_url($profile->mainURL, PHP_URL_HOST); ?>
        </a>
         <a href="/">
            <img class="logo" src="<?= $profile->avatar ?>" alt="" loading="lazy">
            <?= $profile->nick ?>@<?= parse_url($profile->mainURL, PHP_URL_HOST); ?>
        </a> -->
    </p> 
    <nav>

        <ul class="secondary">
            <?php //if ($validSession) {  // TODO: Make login seqcure ?>
            <?php if( isset($_SESSION['password'])) { /*
                if($_SESSION['password']=="$password") {*/ // Hacky login ?>   
                <li><a href="<?= $baseURL ?>/refresh?url=<?= $url ?>"><i class="fa fa-refresh"></i></a></li>
                <li><a href="<?= $baseURL ?>"><i class="fa fa-comments-o">&nbsp;</i>Timeline</a></li>
                <li><a href="<?= $baseURL ?>?profile=<?=$url ?>"><i class="fa fa-user-circle">&nbsp;</i>Profile</a></li>                
                <li><a href="<?= $baseURL ?>/gallery?profile=<?= $profile->mainURL ?>"><i class="fa fa-picture-o"></i>&nbsp;Gallery</a></li>
                <li><a href="<?= $baseURL ?>/following"><i class="fa fa-users">&nbsp;</i>Following <?php // echo count($twtFollowingList); ?></a></li>
                <li><a href="<?= $baseURL ?>/add"><i class="fa fa-user-plus"></i></a></li>
                <li><a href="<?= $baseURL ?>/logout"><i class="fa fa-sign-out">&nbsp;</i>Log Out</a></li>
                <li><?php // include 'partials/lists.php'; ?></li>
            <?php /*}*/ } else { ?>
                <li><a href="<?= $baseURL ?>?profile=<?= $url ?>"><i class="fa fa-user-circle">&nbsp;</i>Profile</a></li>
                <li><a href="<?= $baseURL ?>/gallery?profile=<?= $profile->mainURL ?>"><i class="fa fa-picture-o">&nbsp;</i>Gallery</a></li>
                <li><a href="<?= $baseURL ?>/following"><i class="fa fa-users">&nbsp;</i>Following <?php // echo count($twtFollowingList); ?></a></li>
                <li><a href="<?= $baseURL ?>"><i class="fa fa-comments-o">&nbsp;</i>Timeline</a></li>
                <li><a href="<?= $baseURL ?>/login" class="secondary"><i class="fa fa-sign-in">&nbsp;</i>Log in</a></li>
            <?php }  ?>
        </ul>
    </nav>
</header>

<main>
