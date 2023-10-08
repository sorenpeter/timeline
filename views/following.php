<?php require_once("partials/base.php"); ?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="style.css">
    <title><?= $title ?> - Timeline</title>
</head>
<body >

<!-- PHP: GET HEADER  --><?php include 'partials/header.php';?>

<center>
    <h1>Following: <?php echo count($twtFollowingList); ?> feeds</h1>

    <table>
        <tr><th></th><th>Nick</th><th>URL</th></tr>
    <?php foreach ($twtFollowingList as $currentFollower) { ?>
    <tr>
        <td></td>
        <td><a href="?url=<?= $currentFollower[1] ?>"><?= $currentFollower[0] ?></a></td>
        <td><?= $currentFollower[1] ?>
        <!-- <?php if ($validSession) { ?> -->
        <!-- <a href="?remove_url=<?= $currentFollower[1] ?>">Remove</a> -->
        <!-- <?php } ?> -->
        </td>
    </tr>
    <?php } ?>
    </table>

</center>

<!-- PHP: GET FOOTER  --><?php include 'partials/footer.php';?>

</body>
</html>