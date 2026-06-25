<?php
// Database connection configuration for XAMPP default settings
$servername = "localhost:4306";
$username = "root";       // Default XAMPP username
$password = "";           // Default XAMPP password is empty
$dbname = "sharecare_db";    // Replace this with your actual MySQL database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Database Connection failed: " . mysqli_connect_error());
}

// Set charset to utf8 to avoid text formatting issues
mysqli_set_charset($conn, "utf8");
?>