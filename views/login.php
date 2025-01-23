<?php
require_once 'partials/base.php';

$title = "Login - $title";

// $password comes from libs/session.php
require_once 'libs/session.php';

if (hasValidSession()) {
    header("Location: .");
    die();
} else {
    include 'partials/header.php';
?>
<!-- TODO: Replace center and font tags with CSS -->
<center>
    <h2>Enter password or TOTP</h2>
    <form method="post" action="" id="login_form">
        <input type="password" name="pass" placeholder="Password" autofocus><br>
        <input type="submit" name="submit_pass" value="Login">
        <p><font style="color:red;">
        <?php if (isset($error)) {
            echo $error;
        } ?>
        </font></p>
    </form>
</center>
<?php } ?>

<!-- PHP: GET FOOTER  --><?php include 'partials/footer.php'; ?>