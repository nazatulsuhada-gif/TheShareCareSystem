<?php
session_start();
include("../db.php");

if (!isset($_SESSION['admin'])) {
    header("Location: adminLogin.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Report ID not found.");
}

$report_id = (int)$_GET['id'];


$sql = "SELECT r.*, u.fullname, u.email 
        FROM reports r 
        LEFT JOIN users u ON r.user_id = u.user_id 
        WHERE r.report_id = $report_id";

$result = mysqli_query($conn, $sql);
$report = mysqli_fetch_assoc($result);

if (!$report) {
    die("Report not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Report Details</title>
    <link rel="stylesheet" href="admin.css">
  <style>
    .report-card { 
        max-width: 500px; 
        margin: 50px auto; 
        padding: 30px; 
        border: 1px solid #ccc; 
        background: #fff; 
        border-radius: 12px; 
        box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
    }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; font-weight: bold; color: #555; margin-bottom: 5px; }
    .form-group p { margin: 0; padding: 10px; background: #f9f9f9; border-radius: 5px; border: 1px solid #eee; }
    .btn-back { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #805528; color: white; text-decoration: none; border-radius: 5px; }
</style>

<div class="report-card">
    <h2 style="text-align: center; color: #805528;">Report Details (#<?= htmlspecialchars($report['report_id']) ?>)</h2>
    <hr>

    <div class="form-group">
        <label>Reported User</label>
        <p><?= htmlspecialchars($report['fullname']) ?></p>
    </div>

    <div class="form-group">
        <label>User Email</label>
        <p><?= htmlspecialchars($report['email']) ?></p>
    </div>

    <div class="form-group">
        <label>Date Reported</label>
        <p><?= htmlspecialchars($report['created_at']) ?></p>
    </div>

    <div class="form-group">
        <label>Reason</label>
        <p><?= htmlspecialchars($report['reason']) ?></p>
    </div>

    <div style="text-align: center;">
        <a href="adminReportManagement.php" class="btn-back">← Back to Management</a>    </div>
    </div>

</body>
</html>