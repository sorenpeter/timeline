<?php

// Get info about profile from URL as an objects
if (!empty($_GET['twts'])) {
  $url = $twtsURL;
}

$profile = getTwtsFromTwtxtString($url);

?>  

<div class="profile">

  <a href="<?= $profile->mainURL ?>">
    <img class="avatar" src="<?= $profile->avatar ?>" alt="" loading="lazy">
  </a>

  <div>
      <a href="<?= $profile->mainURL ?>" class="author">
        <strong><?= $profile->nick ?></strong>@<?= parse_url($profile->mainURL, PHP_URL_HOST); ?>
      </a>

    <p><?= $profile->description ?></p>

    <small>
<!--       <a href="">Posts</a> | 
      <a href="">Replies</a> | 
      <a href="">Gallery</a> |
 -->
      <!-- <span class="right"> -->
        <!-- <a href="/following.php">Following <?php echo count($twtFollowingList); ?></a> |  -->
        <a target="_blank" href="<?= $profile->mainURL ?>"></i>twtxt.txt</a> | 
        <a href="https://yarn.social">How to follow</a>
      <!-- </span> -->

    </small>

  </div>

</div>