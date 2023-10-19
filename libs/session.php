<?php
$config = parse_ini_file('private/config.ini');
$password = $config['password'];

session_start();

if(isset($_POST['submit_pass']) && $_POST['pass'])
{
    $pass=$_POST['pass'];
    if($pass=="$password")
    {
        $_SESSION['password']=$pass;
    }
    else
    {
        $error="Incorrect Password";
    }
}
