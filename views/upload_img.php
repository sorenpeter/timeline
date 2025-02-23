<?php
require_once "partials/base.php";
require_once 'libs/session.php';

checkValidSessionOrRedirectToLogin();

$title = "Upload - $title";

include_once 'partials/header.php';

if(!isset($config["media_upload"])) exit("<p class='notice'>The path for <code>media_upload</code> is not set in your <code>config.ini</code></p>");

$media_upload = getcwd() . "/" . $config["media_upload"] .  "/";

?>

<article id="new_twt">
  <form method="POST" enctype="multipart/form-data">
    <strong>Select images to upload:</strong>
    <input type="file" name="files[]" multiple style="width: 100%;border: 1px solid var(--border); margin: 0.5rem 0;"><br>
    <input type="submit" name="submit" value="Upload">
  </form>
</article>

<?php

// Credits: https://www.geeksforgeeks.org/how-to-select-and-upload-multiple-files-with-html-and-php-using-http-post/

session_start(); // Post-Redirect-Get Pattern based on: https://icodemag.com/prg-pattern-in-php-what-why-and-how/
if(isset($_POST['submit'])) {

  $msg = "<p class='notice'>";

  // Configure upload directory and allowed file types
  $upload_dir = $media_upload.DIRECTORY_SEPARATOR;
  $allowed_types = array('jpg', 'png', 'jpeg', 'gif');
  
  // Define maxsize for files i.e 2MB
  $maxsize = 10 * 1024 * 1024;

  // Checks if user sent an empty form
  if(!empty(array_filter($_FILES['files']['name']))) {

    // Loop through each file in files[] array
    foreach ($_FILES['files']['tmp_name'] as $key => $value) {
      
      $file_tmpname = $_FILES['files']['tmp_name'][$key];
      $file_name = $_FILES['files']['name'][$key];
      $file_size = $_FILES['files']['size'][$key];
      $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

      // Apped date to filename
      //$file_name = date('Y-m-d_').$file_name;

      // Replace spaces with dashes and set all lower case
      $file_name = str_replace(' ', '-', strtolower($file_name));

      // Set upload file path
      $file_path = $upload_dir.$file_name;

      // Check file type is allowed or not
      if(in_array(strtolower($file_ext), $allowed_types)) {

        // Verify file size - 2MB max
        if ($file_size > $maxsize)    
          $msg .= "Error: File size is larger than the allowed limit. <br>";

        // If file with name already exists then append time in
        // front of name of the file to avoid overwriting of file
        if(file_exists($file_path)) {
          $msg .= "Error uploading <strong>{$file_name}</strong> - File already exists! <br>";
          // $file_path = $upload_dir.date('Y-m-d_').$file_name;
          
          // if( move_uploaded_file($file_tmpname, $file_path)) {
          //  $msg .= "{$file_name} successfully uploaded <br />";
          // }
          // else {         
          //  $msg .= "Error uploading {$file_name} - File already exists! <br />";
          // }
        }
        else {
        
          if(move_uploaded_file($file_tmpname, $file_path)) {
            
            //$full_url = "http://{$_SERVER['HTTP_HOST']}/twtxt/".$file_name;
            $public_file = $public_media.basename($file_path);

            /*
                    $msg .= '<table class="center"><tr class="preview">';
                        $msg .= '<td><a href="'.$public_file.'">';
                            $msg .= '<img src="'.$public_file.'">';
                        $msg .= '</a></td>';
                    
                        //$file = str_replace('../', $base_url, $file);
                        $msg .= '<td><code>![]('.$public_file.')</code></td>';
                    $msg .= '</tr></table>';
            */

            $msg .= "<strong>{$file_name}</strong> successfully uploaded<br>";
            //$msg .= "<img src='{$file_path}' width='48' height='48'>";
            //$msg .= "<code>![]({$full_url})</code><br />";
            //$msg .= "<img src=".$file_path." height=200 width=300 />";
          }
          else {          
            $msg .= "Error uploading <strong>{$file_name}</strong><br>";
          }
        }
      }
      else {
        
        // If file extension not valid
        //$msg .= '<p class="warning">';
          $msg .= "Error uploading <strong>{$file_name}</strong> for unknown reason";
          $msg .= "({$file_ext} file type is not allowed)<br>";
        //$msg .= '</p>';
      }
    }
  }
  else {
    
    // If no files selected
    $msg .= "No files selected.<br>";
  }

  $msg .= "</p>";

  $_SESSION["message"] = $msg;
  header('Location: '.$_SERVER['REQUEST_URI']);
  exit;
}

// Show message store in session
if (isset($_SESSION["message"])) {
  echo $_SESSION["message"];
  unset($_SESSION["message"]);
}


// Show images already on server and markdown code

$imgs_on_server = glob($media_upload."*.{jpg,jpeg,png,gif}", GLOB_BRACE);

// Sort image files by date (based on: https://stackoverflow.com/questions/124958/glob-sort-array-of-files-by-last-modified-datetime-stamp
usort($imgs_on_server, fn($a, $b) => -(filemtime($a) - filemtime($b)));

echo '<center><em>Copy the code for images and paste it into the new message box</em></center>';

foreach ($imgs_on_server as $img) {

  $public_file = $config["public_media"] . "/" . basename($img);
/*
  echo '<tr class="preview">';
  echo '<td><a href="'.$public_file.'">';
      echo '<img src="'.$public_file.'" style="width:300px; max-height:200px; object-fit: cover;">';
  echo '</a></td>';

  //$img = str_replace('../', $base_url, $img);
  echo '<td><code>![]('.$public_file.')</code></td>';
  echo '</tr>';
*/
  echo '<section class="upload-grid">';
  echo '<code>![]('.$public_file.')</code>';
  echo '<a href="'.$public_file.'"><img src="'.$public_file.'" loading="lazy"></a>';
  echo '</section>';
}

//echo '</table>';

?>



<!-- PHP: FOOTER  --><?php include_once 'partials/footer.php';?>
