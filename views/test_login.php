<?php
# A simple text to check if sessions are working OK
# Remove it from index.php to hide it from the public
require_once "libs/persistent_session.php";

if (!hasValidSession()) {
    #header("Location: /login");
    exit;
}

var_dump($_SESSION);

echo "Valid session";
