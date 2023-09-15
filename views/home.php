<?php
    require_once("partials/session.php"); // TODO: Move all to base.php
?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="../style.css">
    <title>index.html</title>
  </head>
  <body >

<?php
    if($_SESSION['password']=="æøå123") // TODO: Replace by var from config.ini
    {
        ?>
        <h1>You are loggged in now</h1>
        <form method="post" action="" id="logout_form">
          <input type="submit" name="page_logout" value="LOGOUT" style="background-color: #000; color: #f00; font-family: monospace;">
        </form>  
<?php
    } else {
        ?>
        <h1><a href="/login">Log in</a></h1>
<?php } ?>

   </body>
 </html>
