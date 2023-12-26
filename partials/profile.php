<?php

// Get info about profile from URL as an objects
if (!empty($_GET['profile'])) {
  $url = $twtsURL;
}
$profile = getTwtsFromTwtxtString($url);

$profileURL = $baseURL.'/?profile='.$profile->mainURL;

?>  

<div class="profile">

  <a href="<?= $profileURL ?>">
    <img class="avatar" src="<?= $profile->avatar ?>" alt="" loading="lazy">
  </a>

  <div>
      <a href="<?= $profileURL ?>" class="author">
        <strong><?= $profile->nick ?></strong>@<?= parse_url($profile->mainURL, PHP_URL_HOST); ?>
      </a>

    <p><?= $profile->description ?></p>

    <small>
      <a href="<?= $profileURL ?>">Posts</a> | 
      <!-- <a href="">Replies</a> |  -->
      <a href="<?= $baseURL ?>/gallery?profile=<?= $profile->mainURL ?>">Gallery</a>

      <span class="right">
        <!-- <a href="following.php">Following <?php echo count($twtFollowingList); ?></a> |  -->
        <a target="_blank" href="<?= $profile->mainURL ?>"></i><?= $profile->mainURL ?></a>
        (<a href="https://yarn.social">How to follow</a>)
      </span>

    </small>

  </div>

</div>

<!-- <nav>
  <ul>
    <li><a href="">Posts</a></li>
    <li><a href="">Replies</a></li>
    <li><a href="">Gallery</a></li>
  </ul>

   <ul class="right">
    <li><a href="https://yarn.social" class="button">How to follow...</a></li>
    <li><a href="following.php">Following <?php echo count($twtFollowingList); ?></a></li>
    <li><a target="_blank" href="<?= $profile->mainURL ?>"></i>twtxt.txt</a></li>
  </ul>
</nav> -->