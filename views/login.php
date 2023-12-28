<?php
require_once("partials/base.php");

$title = "Login - ".$title;

include 'partials/header.php';
?>

<?php
    //$config = parse_ini_file('private/config.ini');
    //$password = $config['password'];

    if( isset($_SESSION['password'])) {
        if($_SESSION['password']=="$password") {
        header("Location: .");
        die();
        }
    }

    else { ?>
        <center>
        <h2>Enter password:</h2>
        <form method="post" action="" id="login_form">
            <input type="password" name="pass"><br>
            <input type="submit" name="submit_pass" value="Login">
            <p><font style="color:red;"><?php if(isset($error)) {echo $error;}?></font></p>
        </form>
        </center>
<?php } ?>

<!-- PHP: GET FOOTER  --><?php include 'partials/footer.php';?>