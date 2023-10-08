<header>
    <nav>
        <ul>
            <li>🧶 <a href=".">Timeline</a> for
                <a href="http://localhost:8000/?twts=http://darch.dk/twtxt.txt">sorenpeter</a>@darch.dk</li> <!-- TODO: make automatic via PHP and show avatar as well -->
                <li><?php include 'partials/listSelect.php'; ?></li>
        </ul>
        <ul class="secondary">
            <li><a href="load_twt_files.php?url=<?= $url ?>">Refresh</a></li>
            <?php //if ($validSession) {  // TODO: Make login seqcure ?>
            <?php if( isset($_SESSION['password'])) {
                if($_SESSION['password']=="$password") { // Hacky login ?>   
                <li><a href="new_twt.php">New post</a></li>
                <li><a href="follow.php">Add feed</a></li>
                <li><a href="/admin">Settings</a></li>
                <li><form method="post" action="" id="logout_form">
                    <input type="submit" name="page_logout" value="Log out" class="link-btn">
                    </form>
                </li>
            <?php } } else { ?>
                <li><a href="login.php">Log in</a></li>
            <?php }  ?>
            <li><a href="following.php">Following <?php echo count($twtFollowingList); ?> feeds</a></li>            
        </ul>
    </nav>
</header>