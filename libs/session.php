<?php
require_once 'libs/TOTP.php';
require_once 'libs/persistent_session.php';

$config = parse_ini_file('private/config.ini');
$passwordInConfig = $config['password'];

# TODO: Replace using $_SESSION['password'] in other files
# to check for a valid session, as in 'new_twt.php'
# Use hasValidSession() instead

if (isset($_POST['submit_pass']) && $_POST['pass']) {
    $passwordInForm = $_POST['pass'];

    if ($passwordInForm == $passwordInConfig) {
        $_SESSION['password'] = $passwordInForm;
        saveLoginSuccess();
    } elseif ($isCodeValid = verifyTOTP(
        $config['totp_secret'],
        $passwordInForm,
        intval($config['totp_digits'])
    )) {
        $_SESSION['password'] = 'valid_totp';
        saveLoginSuccess();
    } else {
        $error = 'Incorrect Password';
    }
}

# Check for an empty password
if (isset($_POST['submit_pass']) && !$_POST['pass']) {
    $error = 'Type a password';
}
