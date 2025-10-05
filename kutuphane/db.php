<?php
$host = "localhost";
$db = "kutuphane";
$user = "root";
$pass = "";

$conn = mysqli_connect($host, $user, $pass, $db);
if(!$conn){
    die("Bağlantı hatası: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8");
?>
