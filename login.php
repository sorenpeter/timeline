<?php
    require_once("libs/session.php"); // TODO: Move all to base.php
?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="../style.css">
    <title>Log in</title>
  </head>
  <body >

<?php
    //$config = parse_ini_file('private/config.ini');
    //$password = $config['password'];
    
    if( isset($_SESSION['password'])) {
        if($_SESSION['password']=="$password")
        {
        header("Location: .");
        die();
 ?>
        <h1>You are loggged in now</h1>
        <form method="post" action="" id="logout_form">
          <input type="submit" name="page_logout" value="LOGOUT" style="background-color: #000; color: #f00; font-family: monospace;">
        </form>

<?php } } else { ?>
        <h1>Log in:</h1>
        <form method="post" action="" id="login_form">
            <input type="password" name="pass"><br>
            <input type="submit" name="submit_pass" value="Login">
            <p><font style="color:red;"><?php if(isset($error)) {echo $error;}?></font></p>
        </form>
<?php } ?>

   </body>
 </html>
