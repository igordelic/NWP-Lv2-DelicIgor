<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    session_start();
    $txtFile = function ($value) {
        return (pathinfo($value, PATHINFO_EXTENSION) === 'txt');
    };
    if (!is_dir("test/")) {
        echo "<p>No files for decrypting.</p>";
        die();
    }
    $files = array_diff(scandir("test/"), array('..', '.'));
    $files = array_filter($files, $txtFile);
    if (count($files) === 0) {
        echo "<p>No files for decrypting</p>";
    } else {
        echo "<ul>";
        foreach ($files as $file) {
            $nameWithoutExt = substr($file, 0, strlen($file) - 4);
            echo "<li> <a href=\"download.php?file=$nameWithoutExt\">$nameWithoutExt</a></li>";
        }
        echo "</ul>";
    }
    ?>
</body>
</html>



