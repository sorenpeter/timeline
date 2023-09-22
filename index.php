<?php 

//require_once("router.php");
//require_once("views/home.php");
require_once("partials/base.php");

?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Timeline</title>
</head>
<body >

<!-- PHP: GET HEADER  --><?php include 'partials/header.php';?>

<!-- PHP: GET PROFILE CARD  -->
<?php 
if (!empty($_GET['twts'])) { // Show twts for some user
    $twtsURL = $_GET['twts'];

    // TODO: Give a propper error if feed is not valid
    if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
        die('Not a valid URL');
    }

    // $parsedTwtxtFile = getTwtsFromTwtxtString($twtsURL);
    if (!is_null($parsedTwtxtFile)) {
        $parsedTwtxtFiles[$parsedTwtxtFile->mainURL] = $parsedTwtxtFile;
        include 'partials/profile.php';
    }
} ?>

<main class="timeline">

<!-- PHP: GET TIMELIE  --><?php include 'partials/timeline.php'?>

</main>

<!-- PHP: GET FOOTER  --><?php include 'partials/footer.php';?>

</body>
</html> 
