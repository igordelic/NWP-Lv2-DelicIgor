<?php
session_start();

$file = $_GET['file'];

$decryption_key = md5('abcd');
$cipher = "AES-128-CTR";
$options = 0;
$decryption_iv = $_SESSION['iv'];
$contentEncrypted = file_get_contents("test/$file.txt");

$contentDecrypted = base64_decode($contentEncrypted);
$data = openssl_decrypt($contentDecrypted, $cipher, $decryption_key, $options, $decryption_iv);

$file = "test/$file";

file_put_contents($file, $data);

clearstatcache();

if(file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file, true);
    unlink($file);
    die();
}
