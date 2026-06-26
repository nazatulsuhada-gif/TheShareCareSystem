<?php
$conn = mysqli_connect("localhost", "root", "", "sharecare");

if(!$conn)
{
    die("Connection failed: " . mysqli_connect_error());
}
?>