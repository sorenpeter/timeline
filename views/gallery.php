<?php
require_once("partials/base.php");

// require_once 'libs/Thumbnail.php';

$title = "Gallery - ".$title;

include_once 'partials/header.php';

require_once("libs/Cropper.php");
$thumb = new \CoffeeCode\Cropper\Cropper("media/thumbnails", 75, 5, true);

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
    // TODO: default to rendering the local users gallery, if no profile specified
    //echo $profile->mainURL;;
    //$twtsURL = $profile->mainURL; // correct URL for twtxt.txt
}

?>

<?php include_once 'partials/search.php'; ?>

<!-- PHP: GALLERY -->
<div class="gallery">

<?php
foreach ($twts as $twt) {
    $img_array = getImagesFromTwt($twt->content);

    foreach ($img_array as $img) {
        echo '<a href="'.$baseURL.'/conv/'.$twt->hash.'">'.$img[0].'</a>';
    }

/*
    $doc = new DOMDocument();
    $doc->loadHTML($twt->content);
    $img_array = $doc->getElementsByTagName('img');

    foreach ($img_array as $img) {
        $url = $img->getAttribute('src');
        $alt = $img->getAttribute('alt');
        //echo "<br/>Title:" . $img->getAttribute('title');

        echo "<img src='{$thumb->make($url, 200)}' alt='".$alt."' title='".$alt."'>";
    }

/*
    foreach ($img_array as $img) {
        echo $img[0];
        $extension = pathinfo($img, PATHINFO_EXTENSION);
        if (in_array(strtolower($extension), $allowedExtensions)) {
        $imageURL = $galleryDir . $img;
        $thumbURL = $thumbDir . $img;

        if (!file_exists($thumbURL)) {
            createThumbnail($imageURL, $thumbURL, 200); // The thumbnail will have a height of 200 pixels.
        }

        echo '<a href="'.$baseURL.'/conv/'.$twt->hash.'">
                <img src="' . $thumbURL . '" height="200" />
              </a>';
    }


    }
*/


}
?>

</div>

<!-- PHP: FOOTER  --><?php include_once 'partials/footer.php';?>