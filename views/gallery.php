<?php
require_once("partials/base.php");

$title = "Gallery - ".$title;

include_once 'partials/header.php';
?>


<!-- PHP: PROFILE CARD -->
<?php
if (!empty($_GET['profile'])) { // Show twts for some user
    $twtsURL = $_GET['profile'];

    // TODO: Give a propper error if feed is not valid
    if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
        die('Not a valid URL');
    }

    // $parsedTwtxtFile = getTwtsFromTwtxtString($twtsURL);
    if (!is_null($parsedTwtxtFile)) {
        $parsedTwtxtFiles[$parsedTwtxtFile->mainURL] = $parsedTwtxtFile;
        include 'partials/profile.php';
    }
} else {
    // code...
    //$filecontent = file('../twtxt.txt');
    $twtsURL = file($profile->mainURL);
}

?>


<!-- PHP: GALLERY -->
<div class="gallery">

<?php
foreach ($twts as $twt) {
    $img_array = getImagesFromTwt($twt->content);

    foreach ($img_array as $img) {
        //echo '<a href="'.$baseURL.'/post/'.$twt->hash.'">'.$img[0].'</a>';
        // Workaround until cache issue is resolved
        echo '<a href="'.$baseURL.'/?profile='.$twt->mainURL.'#'.$twt->hash.'">'.$img[0].'</a>';
    }
}
?>

</div>

<!-- PHP: FOOTER  --><?php include_once 'partials/footer.php';?>