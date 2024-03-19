<?php
session_start();

$filename = $_FILES['file']['name'];

$location = "test/" . $filename;
$imageFileType = pathinfo($location, PATHINFO_EXTENSION);
$valid_extensions = array("jpg", "jpeg", "png", "pdf");

if (!in_array(strtolower($imageFileType), $valid_extensions)) {
    echo "<p>Invalid format ($imageFileType).</p>";
    die();
}
$content = file_get_contents($_FILES['file']['tmp_name']);
$encryption_key = md5('abcd');
$cipher = "AES-128-CTR";
$iv_length = openssl_cipher_iv_length($cipher);
$options = 0;
$encryption_iv = random_bytes($iv_length);
$encrypted = openssl_encrypt($content, $cipher, $encryption_key, $options, $encryption_iv);
$encryptedData = base64_encode($encrypted);
$_SESSION['iv'] = $encryption_iv;
$fileNameWithoutExt = substr($filename, 0, strpos($filename, "."));
if (!is_dir("uploads/")) {
    if (!mkdir("uploads/", 0777, true)) {
        die("<p>Can not create directory $dir.</p>");
    }
}
$fileNameOnServer = "uploads/${fileNameWithoutExt}.$imageFileType.txt";
file_put_contents($fileNameOnServer, $encryptedData);
echo "File uploaded successfully";