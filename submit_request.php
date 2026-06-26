<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in first.'); window.location.href='login.php';</script>";
    exit();
}

$donation_id = $_GET['donation_id'] ?? '';
$student_id = $_SESSION['user_id']; 

if (empty($donation_id)) {
    echo "<script>alert('Donation id not found.'); window.history.back();</script>";
    exit();
}

$check_sql = "SELECT donation_id FROM donations WHERE donation_id = ? AND is_deleted = 0";
$stmt_check = $conn->prepare($check_sql);
$stmt_check->bind_param("i", $donation_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows === 0) {
    echo "<script>alert('Error: this item is no longer available or not found'); window.history.back();</script>";
    exit();
}

try {
    $stmt = $conn->prepare("INSERT INTO requests (donation_id, student_id, request_status, request_date) VALUES (?, ?, 'pending', NOW())");
    $stmt->bind_param("ii", $donation_id, $student_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Request submitted successfully!'); window.location.href='request.php';</script>";
    }
} catch (mysqli_sql_exception $e) {
    echo "<script>alert('Error : Request submission failed'); window.history.back();</script>";
}
?>