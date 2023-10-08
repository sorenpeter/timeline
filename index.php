<?php 

//require_once("router.php");
//require_once("views/home.php");
//require_once("partials/base.php");

$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);
// $query = parse_url($request, PHP_URL_QUERY);

$viewDir = '/views/';

// Define your routes using regular expressions

$routes = [
    '/' => 'home.php',
    '/admin' => 'admin.php',
    '/about' => 'about.php',
    '/profile/([a-zA-Z0-9_-]+)' => 'profile.php',
    '/conv/([a-zA-Z0-9]{7})' => 'conv.php', // matches only twtHash of exactly 7 alphanumeric characters 
    '/post/([a-zA-Z0-9]{7})' => 'post.php', // matches only twtHash of exactly 7 alphanumeric characters 
];

// Loop through the defined routes and try to match the request URI
foreach ($routes as $pattern => $action) {
    if (preg_match('#^' . $pattern . '$#', $path, $matches)) {
        
        // Extract any matched parameters (e.g., username)      
        if(!empty($matches[1])) {
            //array_shift($matches);
            $id = $matches[1];
        }

        // Load the corresponding action (view)
        require __DIR__ . $viewDir . $action;
        exit; // Stop processing further routes
    }
}

// If no matching route is found, handle as a 404
http_response_code(404);
echo "<h1>Oops! Page not found.</h1>";

/* Credit:
    - PHP FOR BEGINNERS #4 - Create a dynamic Router: https://www.youtube.com/watch?v=eaHBK2XJ5Io
    - https://chat.openai.com/c/3082a22a-d70e-4740-891c-9872f5da2180
*/