<?php

require_once("partials/base.php");

if (!isset($_SESSION['password'])) {
    header('Location: ./login');
    exit();
}

//ob_start();

$title = "Refresh - ".$title;

ob_end_flush();

include 'partials/header.php';

?>

<p id="refreshLabel">
    <span id=refreshInfo>Loading feeds followed by:</span>
    <span id=refreshURL><?= preg_replace('(^https?://)', '', $url) ?><span>
    <span id="refreshCounter">(1 of 10)</span>
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

/* Progress bar based on: https://github.com/w3shaman/php-progress-bar */

$i = 1;
$total = count($twtFollowingList);

foreach ($twtFollowingList as $following) { 
    //ob_start();
    $float = $i/$total;
    $percent = intval($float * 100)."%";
    
    // Javascript for updating the progress bar and information
    echo '<script language="javascript">
            document.getElementById("refreshInfo").innerHTML = "
                <span id=refreshInfo>Updating:</span>
                <span id=refreshURL>'.preg_replace('(^https?://)', '', $following[1]).'<span>
                <span id="refreshCounter">('.$i.' of '.$total.')</span>
            ";
            document.getElementById("refreshProgress").value = "'.$float.'"; 
            document.getElementById("refreshProgress").innerHTML = "'.$percent.'"; 
        </script>';

    updateCachedFile($following[1]);
    ob_flush(); // Send output to browser immediately
    flush();
    $i++;
}

// Tell user that the process is completed
echo '<script language="javascript">
        document.getElementById("refreshLabel").innerHTML="Refreshed '.$total.' feeds";
        history.back();
    </script>';

