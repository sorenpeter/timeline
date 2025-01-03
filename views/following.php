<?php
require_once("partials/base.php");

$title = "Following - " . $title;

include 'partials/header.php';

// TODO: Include profile-card, but only tagcloud for user, not all feeds in cache

?>

<center>
    <h2><img src="<?= $profile->avatar ?>" class="avatar"> <?= $profile->nick ?> follows <?php echo count($twtFollowingList); ?> feeds</h2>

    <table>

        <tr>
            <!-- <th></th> -->
            <th>Nick</th>
            <th>URL</th>
            <?php if (isset($_SESSION['password']) && $_SESSION['password'] == "$passwordInConfig") { ?>
                <th>Time ago</th>
            <?php } ?>
        </tr>

        <?php foreach ($twtFollowingList as $currentFollower) { ?>
            <tr>
                <!-- <td></td> -->
                <td><a href="<?= $baseURL ?>/profile?url=<?= $currentFollower[1] ?>"><?= $currentFollower[0] ?></a></td>
                <!-- <td><a href="/?twt=<?= $currentFollower[1] ?>"><?= $currentFollower[0] ?></a></td> -->
                <td><?= $currentFollower[1] ?>
                    <!-- <?php //if ($validSession) { 
                            ?> -->
                    <!-- <a href="?remove_url=<?= $currentFollower[1] ?>">Remove</a> -->
                    <!-- <?php // } 
                            ?> -->
                </td>
                <?php if (isset($_SESSION['password']) && $_SESSION['password'] == "$passwordInConfig") { ?>
                    <td>
                        <?php
                        // Test first if URL is a valid feed:
                        if (is_array(getTwtsFromTwtxtString($currentFollower[1])->twts)) {

                            // Then test if latest twt is at start or end of file:
                            $resetVar = reset(getTwtsFromTwtxtString($currentFollower[1])->twts);
                            $endVar = end(getTwtsFromTwtxtString($currentFollower[1])->twts);
                            if ($resetVar->timestamp < $endVar->timestamp) { // TODO: this can be swapped to get time of first twt
                                echo $endVar->displayDate;
                            } else {
                                echo $resetVar->displayDate;
                            }
                        }
                        ?>

                    </td>
                <?php } ?>

            </tr>
        <?php } ?>

    </table>

</center>

<!-- FOOTER  --><?php include 'partials/footer.php'; ?>