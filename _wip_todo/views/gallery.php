<!-- PHP: GET CONFIG  --><?php include '../config.php' ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" media="all" href="../layout/default.css?v=1.1">
    <link rel="stylesheet" type="text/css" media="all" href="../custom.css?v=1.1">
    <title><?=$title?> - Gallery</title>
</head>
<body>

<!-- PHP: GET HEADER  --><?php include $base_path.'layout/header.php';?>

<!-- PHP: GET PROFILE CARD  --><?php include $base_path.'layout/profile.php';?>


<div class="gallery">

    <?php include $base_path.'system/gallery.php' ?>

</div>

<!-- PHP: GET FOOTER  --><?php include $base_path.'layout/footer.php';?>


</body>
</html>