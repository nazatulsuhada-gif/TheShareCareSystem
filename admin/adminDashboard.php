<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("../db.php");

if(!isset($_SESSION['admin'])) {
    header("Location: adminLogin.php");
    exit();
}

$userResult = mysqli_query($conn, "SELECT * FROM users");
$userCount = $userResult ? mysqli_num_rows($userResult) : 0;

$donationQuery = mysqli_query($conn, "SELECT * FROM donations");
$donationCount = $donationQuery ? mysqli_num_rows($donationQuery) : 0;

$requestQuery = mysqli_query($conn, "SELECT * FROM requests");
$requestCount = $requestQuery ? mysqli_num_rows($requestQuery) : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - The Share Care</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body>

<?php 
include("navbar.php"); 
?>

<div class="header">
    <h1>Admin Dashboard</h1>
</div>

<div class="container">
    <a href="adminUserManagement.php" style="text-decoration: none; color: inherit;">
        <div class="card" style="cursor: pointer;">
            <h3>Total Users</h3>
            <h2><?php echo $userCount; ?></h2>
        </div>
    </a>

    <a href="adminDonationReview.php" style="text-decoration: none; color: inherit;">
        <div class="card" style="cursor: pointer;">
            <h3>Total Donations</h3>
            <h2><?php echo $donationCount; ?></h2>
        </div>
    </a>

    <a href="adminRequestManagement.php" style="text-decoration: none; color: inherit;">
        <div class="card" style="cursor: pointer;">
            <h3>Total Requests</h3>
            <h2><?php echo $requestCount; ?></h2>
        </div>
    </a>
</div>

</body>
</html>