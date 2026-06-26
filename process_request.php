<?php
include("db.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$action = $_GET['action'] ?? '';
$req_id = isset($_GET['req_id']) ? intval($_GET['req_id']) : 0;

$redirect = $_SERVER['HTTP_REFERER'] ?? 'index.php';

if ($action && $req_id > 0) {
    // 1. Dapatkan maklumat request
    $stmt = $conn->prepare("SELECT student_id, donation_id FROM requests WHERE request_id = ?");
    $stmt->bind_param("i", $req_id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $donation_id = $res['donation_id'] ?? 0;
    $student_id = $res['student_id'] ?? 0;

    if ($donation_id > 0) {
        $redirect = "itemView.php?id=" . $donation_id;
    }

    if ($action == 'accept' && $donation_id > 0) {
        // --- HAD 3 REQUEST SEBULAN ---
        $stmt_limit = $conn->prepare("SELECT COUNT(*) as total FROM requests 
                                      WHERE student_id = ? 
                                      AND request_status = 'approved' 
                                      AND MONTH(request_date) = MONTH(CURRENT_DATE()) 
                                      AND YEAR(request_date) = YEAR(CURRENT_DATE())");
        $stmt_limit->bind_param("i", $student_id);
        $stmt_limit->execute();
        $limit_data = $stmt_limit->get_result()->fetch_assoc();
        
        if ($limit_data['total'] >= 3) {
            $_SESSION['error_message'] = "Minta maaf, anda sudah mencapai had 3 request yang diluluskan untuk bulan ini!";
        } else {
            // --- PROSES KIRA KUOTA ITEM ---
            $stmt_check = $conn->prepare("SELECT quantity FROM donations WHERE donation_id = ?");
            $stmt_check->bind_param("i", $donation_id);
            $stmt_check->execute();
            $item = $stmt_check->get_result()->fetch_assoc();
            $max_qty = (int)$item['quantity'];

            $stmt_count = $conn->prepare("SELECT COUNT(*) as total FROM requests WHERE donation_id = ? AND request_status = 'approved'");
            $stmt_count->bind_param("i", $donation_id);
            $stmt_count->execute();
            $count = $stmt_count->get_result()->fetch_assoc()['total'];

            if ($count < $max_qty) {
                // Update request kepada approved
                $stmt = $conn->prepare("UPDATE requests SET request_status = 'approved' WHERE request_id = ?");
                $stmt->bind_param("i", $req_id);
                $stmt->execute();
            } else {
                $_SESSION['error_message'] = "Minta maaf, had kuantiti item ini telah dicapai!";
            }
        }
    } 
    elseif ($action == 'reject') {
        // TUKAR KEPADA DELETE SUPAYA REKOD HILANG DARI SENARAI
        $stmt = $conn->prepare("DELETE FROM requests WHERE request_id = ?");
        $stmt->bind_param("i", $req_id);
        $stmt->execute();
    }
    elseif ($action == 'report') {
        $stmt = $conn->prepare("UPDATE requests SET request_status = 'reported' WHERE request_id = ?");
        $stmt->bind_param("i", $req_id);
        $stmt->execute();
    }
}

header("Location: " . $redirect);
exit();
?>