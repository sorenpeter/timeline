<?php

$request = $_SERVER['REQUEST_URI'];
$viewDir = '/views/';

switch ($request) {
    case '':
    case '/':
        require __DIR__ . $viewDir . 'home.php';
        break;

    // case '/login':
    //     require __DIR__ . $viewDir . 'login.php';
    //     break;

    // case '/following':
    //     require __DIR__ . $viewDir . 'following.php';
    //     break;

    default:
        // [PHP FOR BEGINNERS #4 - Create a dynamic Router - YouTube](https://www.youtube.com/watch?v=eaHBK2XJ5Io)
        $filename = __DIR__ . $viewDir . $request . ".php";
        if (file_exists($filename)) {
            require $filename;
            break;
        }
        
        http_response_code(404);
        echo "<h1>Oops!</h1>";
        //require __DIR__ . $viewDir . '404.php';
}
