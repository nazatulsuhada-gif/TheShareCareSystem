<?php
$servername = "localhost:4306";
$username = "root";      
$password = "";           
$dbname = "sharecare_db";   

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Database Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");
?>