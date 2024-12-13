<?php
require_once "partials/base.php";

if (!isset($_SESSION['password'])) {
    header('Location: ./login');
    exit();
}

ob_start();

$title = "Refresh - $title";

ob_end_flush();

include 'partials/header.php';
?>
<p id="refreshLabel">
    <strong id="refreshInfo">Loading feeds followed by:</strong>
    <span id="refreshURL"><?= preg_replace('(^https?://)', '', $url) ?></span>
    <span id="refreshCounter"></span>
</p>
<progress id="refreshProgress" value=""></progress>
<?php
include 'partials/footer.php';
ob_start();

flush();

// Get URL from query
$url = $config['public_txt_url'];

if (!empty($_GET['url'])) {
    $url = $_GET['url'];
}

if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
    die('Not a valid URL');
}

// Build Following List
$twtFollowingList = [];

foreach ($fileLines as $currentLine) {
    if (str_starts_with($currentLine, '#')) {
        if (!is_null(getDoubleParameter('follow', $currentLine))) {
            $twtFollowingList[] = getDoubleParameter('follow', $currentLine);
        }
    }
}

// Loop over feeds followed

// Progress bar based on: https://github.com/w3shaman/php-progress-bar
$i = 1;
$total = count($twtFollowingList);

echo '<script language="javascript">document.getElementById("refreshInfo").innerHTML = "Updating feed from:"</script>';

foreach ($twtFollowingList as $following) {
    //ob_start();
    $float = $i / $total;
    $percent = intval($float * 100) . "%";
    $feed = $following[1];
    //$feed = preg_replace('(^https?://)', '', $feed);
    //$feed = $following[0].'@'. parse_url($following[1], PHP_URL_HOST);
    $feed = "{$following[0]} ({$following[1]})";

    // Javascript for updating the progress bar and information
    echo "<script language=\"javascript\">
            document.getElementById(\"refreshURL\").innerHTML = \"$feed\";
            document.getElementById(\"refreshCounter\").innerHTML = \"($i of $total.')\";
            document.getElementById(\"refreshProgress\").value = \"$float\";
            document.getElementById(\"refreshProgress\").innerHTML = \"$percent\";
        </script>";

    updateCachedFile($following[1]);
    ob_flush(); // Send output to browser immediately
    flush();
    $i++;
}

// Tell user that the process is completed
echo '<script language="javascript">
        document.getElementById("refreshInfo").innerHTML="Refreshed '.$total.' feeds from:";
        document.getElementById("refreshURL").innerHTML = "'.preg_replace('(^https?://)', '', $url).'";
        document.getElementById("refreshCounter").innerHTML = "";
        history.back();
    </script>';
