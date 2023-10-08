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

  <section>
      <a href="<?= $profile->mainURL ?>" class="author">
        <strong><?= $profile->nick ?></strong>@<?= parse_url($profile->mainURL, PHP_URL_HOST); ?>
      </a>

    <blockquote><?= $profile->description ?></blockquote>

  </section>

<!--   <aside>
    <small>
        <a href="https://yarn.social" class="button">How to follow...</a>
    </small>  
  </aside> -->

</div>

<nav class="profile">
  <ul>
    <li><a href="">Posts</a></li>
    <li><a href="">Replies</a></li>
    <li><a href="">Gallery</a></li>
    <li><a target="_blank" href="<?= $profile->mainURL ?>"></i>twtxt.txt</a></li>
  </ul>
</nav>