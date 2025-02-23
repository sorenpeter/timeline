<?php
require_once "partials/base.php";
require_once 'libs/session.php';

checkValidSessionOrRedirectToLogin();

$title = "Upload - $title";

include_once 'partials/header.php';

?>

<article>

  <form action="" method="post" enctype="multipart/form-data">
    <label>Select image to upload</label>
    <input type="file" name="fileToUpload" id="fileToUpload"><br>
    <input type="submit" value="Upload Image" name="submit">
  </form>

</article>

<?php

$media_upload = getcwd() . "/" . $config["media_upload"] .  "/";

if (!empty($_POST)) {
  // Based on code from: https://www.w3schools.com/php/php_file_upload.asp

  //echo getcwd() ."<br>";
  //echo __DIR__ . "<br>";
  //echo "upload path: " . $config["media_upload"];

  $target_file = $media_upload . basename($_FILES["fileToUpload"]["name"]);
  $target_file = str_replace(' ', '-', strtolower($target_file)); // Replace spaces with dashes and set all lower case
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  echo "<p class='notice'>";

  // Check if image file is a actual image or fake image
  if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
      //echo "File is an image - " . $check["mime"] . ".<br>";
      $uploadOk = 1;
    } else {
      echo "File is not an image.<br>";
      $uploadOk = 0;
    }
  }

  // Check if file already exists
  if (file_exists($target_file)) {
    echo "Sorry, file already exists.<br>";
    $uploadOk = 0;
  }

  // Check file size
  if ($_FILES["fileToUpload"]["size"] > 5000000) {
    echo "Sorry, your file is too large.<br>";
    $uploadOk = 0;
  }

  // Allow certain file formats
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>";
    $uploadOk = 0;
  }

  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.<br>";
  // if everything is ok, try to upload file
  } else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      echo "The file <code>". htmlspecialchars( basename($target_file)). "</code> has been uploaded.<br>";
    } else {
      echo "Sorry, there was an error uploading your file.<br>";
    }
  }

  echo "</p>";

}


// Show images already on server and markdown code

$imgs_on_server = glob($media_upload."*.{jpg,jpeg,png,gif}", GLOB_BRACE);

// Sort image files by date (based on: https://stackoverflow.com/questions/124958/glob-sort-array-of-files-by-last-modified-datetime-stamp
usort($imgs_on_server, fn($a, $b) => -(filemtime($a) - filemtime($b)));

echo '<table class="center">';

foreach ($imgs_on_server as $img) {

  $public_file = $config["public_media"] . "/" . basename($img);

  echo '<tr class="preview">';
  echo '<td><a href="'.$public_file.'">';
  echo '<img src="'.$public_file.'" style="width=50px;">';
  echo '</a></td>';

  //$img = str_replace('../', $base_url, $img);
  echo '<td><code>![]('.$public_file.')</code></td>';
  echo '</tr>';
}

echo '</table>';

?>



<!-- PHP: FOOTER  --><?php include_once 'partials/footer.php';?>
