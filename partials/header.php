<?php
require_once 'libs/session.php';

$profile = getTwtsFromTwtxtString($config['public_txt_url']);
?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" type="image/x-icon" href="<?= $baseURL ?>/media/logo.png">
    <?php if (hasValidSession()) { ?>
        <script src="<?= $baseURL ?>/libs/tiny-mde.min.js"></script>
        <link rel="stylesheet" type="text/css" href="<?= $baseURL ?>/libs/tiny-mde.css" />
    <?php } ?>
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
        <a href="<?= $baseURL ?>">
            <img class="logo" src="<?= $baseURL ?>/media/logo.png">
            <?= $config['site_title']; ?>
        </a>
        <!--
        <a href="/">
            <img class="logo" src="<?= $profile->avatar ?>" alt="" loading="lazy">
            <?= parse_url($profile->mainURL, PHP_URL_HOST); ?>
        </a>
        <a href="/">
            <img class="logo" src="<?= $profile->avatar ?>" alt="" loading="lazy">
            <?= $profile->nick ?>@<?= parse_url($profile->mainURL, PHP_URL_HOST); ?>
        </a>
        -->
    </p>
    <nav>

        <ul class="secondary">
            <?php //if ($validSession) {  // TODO: Make login secure ?>
            <?php if (hasValidSession()) { // Hacky login ?>
                <li><a href="<?= $baseURL ?>/refresh?url=<?= $url ?>"><i class="fa fa-refresh"></i><span>Refresh</span></a></li>
                <li><a href="<?= $baseURL ?>"><i class="fa fa-comments-o"></i><span>Timeline</span></a></li>
                <?php if (!empty($config['public_webmentions'])) { ?>
                    <li><a href="<?= $baseURL ?>/profile?url=<?= $config['public_webmentions'] ?>"><i class="fa fa-at"></i><span>Mentions</span></a></li>
                <?php }  ?>
                <li><a href="<?= $baseURL ?>/profile"><i class="fa fa-user-circle"></i><span>Profile</span></a></li>
                <li><a href="<?= $baseURL ?>/gallery?url=<?= $config['public_txt_url'] ?>"><i class="fa fa-picture-o"></i><span>Gallery</span></a></li>
                <li><a href="<?= $baseURL ?>/following"><i class="fa fa-users"></i><span>Following <?php // echo count($twtFollowingList); ?></span></a></li>
                <li><a href="<?= $baseURL ?>/add"><i class="fa fa-user-plus"></i><span>Add feed</span></a></li>
                <li><a href="<?= $baseURL ?>/logout"><i class="fa fa-sign-out"></i><span>Log Out</span></a></li>
                <li><?php // include 'partials/lists.php'; ?></li>
            <?php /*}*/ } else { ?>
                <li><a href="<?= $baseURL ?>/profile"><i class="fa fa-user-circle"></i><span>Profile</span></a></li>
                <li><a href="<?= $baseURL ?>/gallery?url=<?= $config['public_txt_url'] ?>"><i class="fa fa-picture-o"></i><span>Gallery</span></a></li>
                <li><a href="<?= $baseURL ?>/following"><i class="fa fa-users"></i><span>Following <?php // echo count($twtFollowingList); ?></span></a></li>
                <li><a href="<?= $baseURL ?>?url=<?= $config['public_txt_url'] ?>"><i class="fa fa-comments-o"></i><span>Timeline</span></a></li>
                <li><a href="<?= $baseURL ?>/login" class="secondary"><i class="fa fa-sign-in"></i><span>Log in</span></a></li>
            <?php }  ?>
        </ul>
    </nav>
</header>

<main>
