<?php
session_start(); 
include("db.php");

if (isset($_SESSION['user_id']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id']; 
    $donation_id = $_POST['donation_id'];
    $reason = $_POST['reason'];

    $stmt = $conn->prepare("INSERT INTO reports (donation_id, user_id, reason) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $donation_id, $user_id, $reason);
    $stmt->execute();
}
?>