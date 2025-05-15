<?php
// config/db.php

$host = "localhost";
$username = "root";
$password = "";
$database = "kuliah";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>