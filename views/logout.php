<?php
require_once "libs/persistent_session.php";

deletePersistentCookie();
session_unset();
session_destroy();

header("Location: .");
die();
