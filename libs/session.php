<?php
require_once 'libs/TOTP.php';
require_once 'libs/persistent_session.php';

$config = parse_ini_file('private/config.ini');
$passwordInConfig = $config['password'];

function checkValidSessionOrRedirectToLogin() {
    if (!hasValidSession()) {
        header('Location: ./login');
        exit();
    }
}

if (isset($_POST['submit_pass']) && $_POST['pass']) {
    $passwordInForm = $_POST['pass'];

    if ($passwordInForm == $passwordInConfig) {
        $_SESSION['password'] = $passwordInForm;
        saveLogin();
    } elseif ($isCodeValid = verifyTOTP(
        $config['totp_secret'],
        $passwordInForm,
        intval($config['totp_digits'])
    )) {
        $_SESSION['password'] = 'valid_totp';
        saveLogin();
    } else {
        $error = 'Incorrect Password';
    }
}

# Check for an empty password
if (isset($_POST['submit_pass']) && !$_POST['pass']) {
    $error = 'Type a password';
}
