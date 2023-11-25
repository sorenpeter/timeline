<?php
require_once('libs/TOTP.php');
$config = parse_ini_file('private/config.ini');
$password = $config['password'];

session_start();

if (isset($_POST['submit_pass']) && $_POST['pass'])
{
    $pass = $_POST['pass'];

    // @eapl.me 2023-11-23 - I'm trying to add support to passwords
    // and TOTP (passwordless). So, in the Pwd field you can enter
    // the password, or the current TOTP
    if ($pass == $password)
    {
        $_SESSION['password'] = $pass;
    }
    elseif ($isCodeValid = verifyTOTP(
        $config['totp_secret'], $pass, intval($config['totp_digits']))
    )
    {
        // If TOTP is valid, assume that we entered the Password
        $_SESSION['password'] = $password;
    }
    else
    {
        $error = "Incorrect Password";
    }
}
