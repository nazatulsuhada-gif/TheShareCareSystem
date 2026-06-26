<?php
session_start();
include("../db.php");

if (!isset($_SESSION['admin'])) {
    header("Location: adminLogin.php");
    exit();
}


$search_query = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $search_query = " WHERE u.fullname LIKE '%$search%' OR r.reason LIKE '%$search%' ";
}


$sql = "SELECT r.report_id, r.reason, r.created_at, u.fullname AS reported_user_name 
        FROM reports r
        LEFT JOIN users u ON r.user_id = u.user_id 
        $search_query
        ORDER BY r.report_id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Report Management</title>
    <link rel="stylesheet" href="admin.css">
   <style>
    h1 { color: #805528; font-size: 28px; margin-top: 30px; margin-bottom: 20px; font-weight: bold; text-align: center; }

    table {
        width: 85%;
        border-collapse: collapse;
        background: white;
        margin: 20px auto;
        box-shadow: 0 2px 8px gray;
    }

    th {
        background: #805528;
        color: white;
        padding: 15px;
        text-align: center;
    }

    td {
        padding: 15px;
        border: 1px solid lightgray;
        text-align: center;
    }

    tr:hover { background-color: #faf6d1; }

    .btn-view {
        background: #805528;
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        font-size: 13px;
        display: inline-block;
    }
    
    .btn-view:hover { background: #6b451f; }
</style>
</head>

<body>

    <?php include("navbar.php"); ?>

    <h1>Report Management</h1>

    <center>
        <?php include("search.php"); ?>
        <br>

        <table>
            <thead>
                <tr>
                    <th style="width: 12%;">Report ID</th>
                    <th style="width: 25%;">Reported User</th>
                    <th style="width: 35%;">Reason</th>
                    <th style="width: 15%;">Date Reported</th>
                    <th style="width: 13%;">Action</th>
                </tr>
            </thead>
            <tbody>
    <?php 
    if ($result && mysqli_num_rows($result) > 0) {
        $counter = 1; // 1. Mulakan pembilang di sini
        while ($row = mysqli_fetch_assoc($result)) {
    ?>
            <tr>
                <td><strong>#<?= $counter++ ?></strong></td>
                
                <td style="font-weight: 600; color: #5a3b1a;">
                    <?= htmlspecialchars($row['reported_user_name'] ?? 'Unknown User') ?>
                </td>
                <td style="text-align: left; line-height: 1.4;">
                    <?= nl2br(htmlspecialchars($row['reason'])) ?>
                </td>
                <td>
                    <?= date('d-m-Y', strtotime($row['created_at'])) ?>
                </td>
                <td>
                    <a href="adminReportDetails.php?id=<?= $row['report_id'] ?>" class="btn-view">View</a>
                </td>
            </tr>
    <?php 
        }
    } else {
        echo "<tr><td colspan='5' class='text-muted' style='padding: 30px;'>No reports found.</td></tr>";
    }
    ?>
</tbody>
        </table>
    </center>

</body>
</html>