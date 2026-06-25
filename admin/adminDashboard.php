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

$logResult = mysqli_query($conn, "
    SELECT action, target_table, created_at
    FROM admin_logs
    ORDER BY created_at DESC
    LIMIT 5
");

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

    <div class="card">
        <h3>Total Users</h3>
        <h2><?php echo $userCount; ?></h2>
    </div>

    <div class="card">
        <h3>Total Donations</h3>
        <h2><?php echo $donationCount; ?></h2>
    </div>

    <div class="card">
        <h3>Total Requests</h3>
        <h2><?php echo $requestCount; ?></h2>
    </div>

</div>

<div class="container">

    <h2>Recent Admin Activities</h2>

    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <tr>
            
            <th>Action</th>
            <th>Target Table</th>
            <th>Date & Time</th>
        </tr>

        <?php if (mysqli_num_rows($logResult) > 0): ?>

    <?php while($log = mysqli_fetch_assoc($logResult)) { ?>
        <tr>
            <td><?= htmlspecialchars($log['action']) ?></td>
            <td><?= htmlspecialchars($log['target_table']) ?></td>
            <td><?= htmlspecialchars($log['created_at']) ?></td>
        </tr>
    <?php } ?>

<?php else: ?>

    <tr>
        <td colspan="3" style="text-align:center;">
            No admin activities found.
        </td>
    </tr>

<?php endif; ?>

    </table>

</div>
</body>
</html>