<?php
require_once("partials/base.php");

$title = "Following - ".$title;

include 'partials/header.php';
?>

<center>
    <h1>Following <?php echo count($twtFollowingList); ?> feeds</h1>

    <table>
    
        <tr>
            <!-- <th></th> -->
            <th>Nick</th>
            <th>URL</th></tr>

        <?php foreach ($twtFollowingList as $currentFollower) { ?>
        <tr>
            <!-- <td></td> -->
            <td><a href="<?= $baseURL ?>/?profile=<?= $currentFollower[1] ?>"><?= $currentFollower[0] ?></a></td>
            <!-- <td><a href="/?twt=<?= $currentFollower[1] ?>"><?= $currentFollower[0] ?></a></td> -->
            <td><?= $currentFollower[1] ?>
            <!-- <?php if ($validSession) { ?> -->
            <!-- <a href="?remove_url=<?= $currentFollower[1] ?>">Remove</a> -->
            <!-- <?php } ?> -->
            </td>
        </tr>
        <?php } ?>

    </table>

</center>

<!-- FOOTER  --><?php include 'partials/footer.php';?>