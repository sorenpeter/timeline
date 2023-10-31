<?php
require_once("partials/base.php");

$title = "Profile for"." - ".$title;

include('partials/header.php');
?>

<!-- PHP: PROFILE CARD -->
<?php

$twtsURL = $config['public_txt_url'];
// $twtsURL = "http://darch.dk/twtxt.txt";

/* from base.php */

# Show twts only for URL in query request, else show user timeline

if (!empty($_GET['url'])) { // Show twts for some user
    echo "before GET(url): ".$twtsURL."<br>";
    $twtsURL = $_GET['url'];
    echo "after GET(url): ".$twtsURL."<br>";

    if (filter_var($twtsurl, FILTER_VALIDATE_URL) === FALSE) {
        die('Not a valid URL');
    }

    $parsedTwtxtFile = getTwtsFromTwtxtString($twtsURL);
    if (!is_null($parsedTwtxtFile)) {
        $parsedTwtxtFiles[$parsedTwtxtFile->mainURL] = $parsedTwtxtFile;
    }

} else { // Show timeline for the URL
    $twtsURL = $config['public_txt_url'];
    //$twtsURL = "http://darch.dk/twtxt.txt";

    if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
        die('Not a valid URL');
    }

    $parsedTwtxtFile = getTwtsFromTwtxtString($twtsURL);
    if (!is_null($parsedTwtxtFile)) {
        $parsedTwtxtFiles[$parsedTwtxtFile->mainURL] = $parsedTwtxtFile;
    }
}

$twts = [];

# Combine all the followers twts
foreach ($parsedTwtxtFiles as $currentTwtFile) {
    if (!is_null($currentTwtFile)) {
        $twts += $currentTwtFile->twts;
    }
}

krsort($twts, SORT_NUMERIC);


/* Profile header 

if (empty($_GET['url'])) { // Show twts for some user
    $twtsURL = $config['txt_file_path'];

    if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
        die('Not a valid URL');
    }

    $parsedTwtxtFile = getTwtsFromTwtxtString($twtsURL);
    if (!is_null($parsedTwtxtFile)) {
        $parsedTwtxtFiles[$parsedTwtxtFile->mainURL] = $parsedTwtxtFile;
    }
}
*/

echo $twtsURL;

$profile = getTwtsFromTwtxtString($twtsURL);


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


<!-- PHP: NEW POST BOX -->
<?php
if( isset($_SESSION['password'])) { 
    include 'views/new_twt.php'; // TODO: Split up new_twt into a view and a partial
} ?>

<!-- PHP: TIMELINE --><?php include 'partials/timeline.php'?>

<!-- PHP: FOOTER  --><?php include 'partials/footer.php';?>

