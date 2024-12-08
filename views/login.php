<?php
require_once("partials/base.php");

$title = "Login - ".$title;

    // Password comes from libs/session.php
    if (isset($_SESSION['password'])) {
        if ($_SESSION['password'] == $password) {
            header("Location: .");
            include 'partials/header.php';
            die();
        }
    }

    else { 
      include 'partials/header.php';
?>
        <center>
        <h2>Enter password or TOTP</h2>
        <form method="post" action="" id="login_form">
            <input type="password" name="pass" placeholder="Password" autofocus><br>
            <input type="submit" name="submit_pass" value="Login">
            <p><font style="color:red;"><?php if(isset($error)) {echo $error;}?></font></p>
        </form>
        </center>
<?php } ?>

<!-- PHP: GET FOOTER  --><?php include 'partials/footer.php';?>
