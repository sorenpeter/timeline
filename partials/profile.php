<?php

// Get info about profile from URL as an objects
if (!empty($_GET['profile'])) {
	$url = $twtsURL;
}

$profile = getTwtsFromTwtxtString($url);
$profileURL = $baseURL . '/?profile=' . $profile->mainURL;
$textareaValue = "@<$profile->nick $profile->mainURL> ";
?>

<div class="profile">

  <a href="<?=$profile->avatar?>">
    <img class="avatar" src="<?=$profile->avatar?>" onerror="this.onerror=null;this.src='<?= $baseURL ?>/media/default.png';">
  </a>

  <div>
      <a href="<?=$profileURL?>" class="author">
        <strong><?=$profile->nick?></strong>@<?=parse_url($profile->mainURL, PHP_URL_HOST);?>
      </a>

    <p><?=$profile->description?></p>

    <small>
      <a href="<?=$profileURL?>">Posts</a> |
      <!-- <a href="">Replies</a> |  -->
      <a href="<?=$baseURL?>/gallery?profile=<?=$profile->mainURL?>">Gallery</a>

      <span class="right">
        <!-- <a href="following.php">Following <?php echo count($twtFollowingList); ?></a> |  -->
        <a target="_blank" href="<?=$profile->mainURL?>"></i><?=$profile->mainURL?></a>
        (<a href="https://yarn.social">How to follow</a>)
      </span>

    </small>

  </div>

</div>
