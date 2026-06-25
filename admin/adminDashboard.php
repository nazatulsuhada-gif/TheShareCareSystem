<?php
session_start();
include("../db.php");

if(!isset($_SESSION['admin']))
{
    header("Location: /thesharecaresystem/admin/adminLogin.php");
    exit();
}


$userResult = mysqli_query($conn, "SELECT * FROM users");

if(!$userResult){
    die("User table error: " . mysqli_error($conn));
}

$userCount = mysqli_num_rows($userResult);


$donationCount = mysqli_num_rows(
    mysqli_query($conn, "SELECT * FROM donations")
);

$requestCount = mysqli_num_rows(
    mysqli_query($conn, "SELECT * FROM requests")
);

$logResult = mysqli_query($conn, 
    "SELECT action, target_table, created_at
    FROM admin_logs
    ORDER BY created_at DESC
    LIMIT 5"
    );
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - The Share Care</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body>

<?php include("navbar.php"); ?>

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
</div>
</body>
</html>