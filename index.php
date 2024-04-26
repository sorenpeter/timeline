<?php 

function getBaseURI() // https://github.com/taniarascia/comments/issues/26#issuecomment-1458121921
{
    $basePath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
    // Get the current Request URI and remove rewrite base path from it
    // (= allows one to run the router in a sub folder)
    $uri = substr(rawurldecode($_SERVER['REQUEST_URI']), strlen($basePath));
    // Don't take query params into account on the URL
    if (strstr($uri, '?')) {
        $uri = substr($uri, 0, strpos($uri, '?'));
    }
    // Remove trailing slash + enforce a slash at the start
    return '/' . trim($uri, '/');
}

$request = getBaseURI();
//$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);
// $query = parse_url($request, PHP_URL_QUERY);


$viewDir = '/views/';

// Define your routes using regular expressions

// TODO: re-add auto detection of files in /views as routes
$routes = [
    '/' => 'home.php',
    '/new' => 'new_twt.php',
    '/add' => 'add_feed.php',
    '/following' => 'following.php',
    //'/refresh' => 'load_twt_files.php',
    '/refresh' => 'refresh.php',
    '/login' => 'login.php',
    '/logout' => 'logout.php',
    '/profile' => 'profile.php',
    '/replies' => 'replies.php',
    '/gallery' => 'gallery.php',
    //'/profile/([a-zA-Z0-9_-]+)' => 'profile.php',
    '/conv/([a-zA-Z0-9]{7})' => 'conv.php', // matches only twtHash of exactly 7 alphanumeric characters 
    '/post/([a-zA-Z0-9]{7})' => 'post.php', // matches only twtHash of exactly 7 alphanumeric characters 
    //'/thumb' => 'thumb.php',
    '/upload' => 'upload_img.php',
    '/webmention' => 'webmention_endpoint.php',
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

//echo __DIR__ . $viewDir . $action;

/* Credit:
    - PHP FOR BEGINNERS #4 - Create a dynamic Router: https://www.youtube.com/watch?v=eaHBK2XJ5Io
    - https://chat.openai.com/c/3082a22a-d70e-4740-891c-9872f5da2180
*/
