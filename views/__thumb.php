<?php
/**
 * @description
 * Thumbnails on the fly with cache from local and remote images
 *
 * Parameters:
 * image      absolute path of local image starting with "/" (e.g. /images/toast.jpg)
 * width      width of final image in pixels (e.g. 700)
 * height     height of final image in pixels (e.g. 700)
 * nocache    (optional) does not read image from the cache
 * quality    (optional, 0-100, default: 90) quality of output image
 *
 * @example
 * <img src="/thumb.php?width=100&height=100&image=http://url.to/image.jpg" />
 *
 * @see https://gist.github.com/dmkuznetsov/f0038b578c1cb6ccd44d
 * @license MIT
 */
define('DEFAULT_WIDTH', 300);
define('DEFAULT_HEIGHT', 300);
define('DEFAULT_QUALITY', 100);
define('CACHE_DIR', rtrim(sys_get_temp_dir(), '/') . '/thumbnails/');
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);

//if (!isset($_GET['image'], $_GET['width'], $_GET['height'])) {
if (!isset($_GET['image'])) {
    header('HTTP/1.1 400 Bad Request');
    //echo 'Error: no image/width/height was specified';
    echo 'Error: no image was specified';
    exit();
}

// Made width and height optional and define their defaults.
// by: sp@darch.dk (with help from chatGPT, 2024-02-03)
if (!isset($_GET['width'], $_GET['height'])) { 
    $resizeWidth = $width = DEFAULT_WIDTH;
    $resizeHeight = $height = DEFAULT_HEIGHT;
} else {
    $resizeWidth = $width = (int)$_GET['width'];
    $resizeHeight = $height = (int)$_GET['height'];
} 

$quality = (isset($_GET['quality'])) ? (int)$_GET['quality'] : DEFAULT_QUALITY;
$nocache = isset($_GET['nocache']);

if (!is_dir(CACHE_DIR)) {
    mkdir(CACHE_DIR, 0755, true);
}

// Make sure we can read and write the cache directory
if (!is_readable(CACHE_DIR)) {
    header('HTTP/1.1 500 Internal Server Error');
    echo 'Error: the cache directory is not readable';
    exit();
} else if (!is_writable(CACHE_DIR)) {
    header('HTTP/1.1 500 Internal Server Error');
    echo 'Error: the cache directory is not writable';
    exit();
}

$originalImageSource = (string)$_GET['image'];
$image = getImagePath($originalImageSource);

if ($image[0] != '/' || strpos(dirname($image), ':') || preg_match('/(\.\.|<|>)/', $image)) {
    header('HTTP/1.1 400 Bad Request');
    echo 'Error: malformed image path. Image paths must begin with \'/\'';
    exit();
}

if (!$image || !file_exists($image)) {
    header('HTTP/1.1 404 Not Found');
    echo 'Error: image does not exist: ' . $image;
    exit();
}

$size = getimagesize($image);
$mime = $size['mime'];

if (substr($mime, 0, 6) != 'image/') {
    header('HTTP/1.1 400 Bad Request');
    echo 'Error: requested file is not an accepted type: ' . $image;
    exit();
}

$originalWidth = $size[0];
$originalHeight = $size[1];
$thumbHash = md5($image . $width . $height . $quality);
$thumbPath = CACHE_DIR . $thumbHash;

if (!$nocache && file_exists($thumbPath)) {
    $imageModified = filemtime($image);
    $thumbModified = filemtime($thumbPath);
    if ($imageModified < $thumbModified) {
        $data = file_get_contents($thumbPath);
        findBrowserCache(md5($data), gmdate('D, d M Y H:i:s', $thumbModified) . ' GMT');
        header("Content-type: $mime");
        header('Content-Length: ' . strlen($data));
        echo $data;
        exit();
    }
}

$saveAlphaChannel = false;
$imageCreationFunction = 'imagecreatefromjpeg';
$imageOutputFunction = 'imagejpeg';
$dst = imagecreatetruecolor($width, $height);
switch ($size['mime']) {
    case 'image/gif':
        $imageCreationFunction = 'imagecreatefromgif';
        $imageOutputFunction = 'imagepng';
        $quality = round(10 - ($quality / 10));
        $saveAlphaChannel = true;
        break;
    case 'image/x-png':
    case 'image/png':
        $imageCreationFunction = 'imagecreatefrompng';
        $imageOutputFunction = 'imagepng';
        $quality = round(10 - ($quality / 10));
        $saveAlphaChannel = true;
        break;
}
$src = $imageCreationFunction($image);

if ($saveAlphaChannel) {
    imagealphablending($dst, false);
    imagesavealpha($dst, true);
}

$ratio = $originalWidth / $originalHeight;
if ($width / $height <= $ratio) {
    $resizeWidth = $height * $ratio;
} else {
    $resizeHeight = $width / $ratio;
}

// This is where the image is being resized
imagecopyresampled($dst, $src, 0, 0, 0, 0, $resizeWidth, $resizeHeight, $originalWidth, $originalHeight);

$imageOutputFunction($dst, $thumbPath, $quality);
ob_start();
$imageOutputFunction($dst, null, $quality);
$data = ob_get_contents();
ob_end_clean();

imagedestroy($src);
imagedestroy($dst);

if ($nocache && file_exists($image)) {
    unlink($image);
}

findBrowserCache(md5($data), gmdate('D, d M Y H:i:s', filemtime($thumbPath)) . ' GMT');
header("Content-type: $mime");
header('Content-Length: ' . strlen($data));
echo $data;
exit;

function getImagePath($image)
{
    if (!filter_var($image, FILTER_VALIDATE_URL)) {
        $image = DOCUMENT_ROOT . '/' . ltrim($image, '/');
    } else {
        $ext = pathinfo(preg_replace('/^(s?f|ht)tps?:\/\/[^\/]+/i', '', $image), PATHINFO_EXTENSION);
        $tmpImagePath = CACHE_DIR . md5($image);
        if ($ext) {
            $tmpImagePath .= '.' . $ext;
        }
        if (!file_exists($tmpImagePath)) {
            $imageContent = file_get_contents($image);
            if (!$imageContent) {
                header('HTTP/1.1 400 Bad Request');
                echo 'Error: can not download image';
                exit();
            } else {
                file_put_contents($tmpImagePath, $imageContent);
            }
        }
        $image = $tmpImagePath;
    }
    return $image;
}


function findBrowserCache($tag, $lastModified)
{
    header("Last-Modified: $lastModified");
    header("ETag: \"{$tag}\"");

    $ifNoneMatch = isset($_SERVER['HTTP_IF_NONE_MATCH']) ?
        stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) :
        false;

    $ifModifiedSince = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ?
        stripslashes($_SERVER['HTTP_IF_MODIFIED_SINCE']) :
        false;

    if (!$ifModifiedSince && !$ifNoneMatch) {
        return;
    }

    if ($ifNoneMatch && $ifNoneMatch != $tag && $ifNoneMatch != '"' . $tag . '"') {
        return;
    }

    if ($ifModifiedSince && $ifModifiedSince != $lastModified) {
        return;
    }

    header('HTTP/1.1 304 Not Modified');
    exit();
}