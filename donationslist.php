<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* 1. COMPLETE HANDLER - Pastikan ia update kedua-dua table */
if (isset($_GET['complete'])) {
    $completeId = intval($_GET['complete']);
    
    // Mula transaksi untuk keselamatan data
    $conn->begin_transaction();
    
    try {
        // 1. Update status di table donations
        $stmt1 = $conn->prepare("UPDATE donations SET status = 'completed' WHERE donation_id = ? AND user_id = ?");
        $stmt1->bind_param("ii", $completeId, $user_id);
        $stmt1->execute();
        
        // 2. Update status di table requests kepada 'completed'
        $stmt2 = $conn->prepare("UPDATE requests SET request_status = 'completed', completed_at = NOW() WHERE donation_id = ?");
        $stmt2->bind_param("i", $completeId);
        $stmt2->execute();
        
        $conn->commit(); // Simpan perubahan jika semua berjaya
        header("Location: donationslist.php");
        exit();
    } catch (Exception $e) {
        $conn->rollback(); // Batalkan jika ada ralat
        echo "Error updating status: " . $e->getMessage();
    }
}

/* 2. FETCH DATA - Kemaskini SQL */
// Buang sekatan 'approved'/'completed' supaya semua barang donor muncul
$sql = "SELECT d.*, u.fullname, r.request_date, r.request_id, r.request_status, r.completed_at 
        FROM donations d 
        LEFT JOIN requests r ON d.donation_id = r.donation_id 
        LEFT JOIN users u ON r.student_id = u.user_id 
        WHERE d.user_id = ? 
        AND d.is_deleted = 0
        ORDER BY d.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donations List</title>
    <link rel="stylesheet" href="donor.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .section-title { width: 90%; max-width: 1000px; margin: 20px auto 10px; font-weight: bold; font-size: 18px; }
        .items-grid { width: 90% !important; max-width: 1000px; margin: 20px auto; display: flex; flex-direction: column; }
        .item-card { display: flex; flex-direction: row; align-items: center; padding: 15px; border: 1px solid #ddd; border-radius: 10px; margin-bottom: 10px; background: #fff; }
        .item-image { width: 80px; height: 80px; border-radius: 8px; overflow: hidden; margin-right: 15px; flex-shrink: 0; background:#eee; display:flex; align-items:center; justify-content:center; }
        .actions { margin-left: auto; display: flex; gap: 10px; align-items: center; }
.complete-btn {
    background: #805528; 
    color: white !important; 
    padding: 10px 20px; /* Tambah padding untuk jadikan butang lebih besar */
    border-radius: 6px; 
    text-decoration: none; 
    font-size: 14px;    /* Besarkan sedikit saiz tulisan */
    font-weight: bold; 
    display: inline-block;
    transition: background 0.3s;
}

/* Tambah kesan hover supaya nampak lebih interaktif */
.complete-btn:hover {
    background: #a06b33; 
}    
.done-text {
    color: green;
    font-weight: bold;
    font-size: 14px;    /* Samakan dengan font-size complete-btn */
    padding: 10px 20px; /* Samakan padding dengan complete-btn */
    display: inline-block;
    border: 1px solid transparent; /* Untuk pastikan ketinggian sama */
}
</style>
</head>
<body>

    <div class="header">
        <a href="#" onclick="history.back(); return false;"> 
            <img src="images/back.png" class="icon-img">
        </a>
        <div class="logo">⚪ THE SHARE CARE</div>
        <a href="index.php"><img src="images/home.png" class="icon-img"></a>
    </div>

    <div class="section-title">My Donation (Completed / Approved)</div>

    <div class="items-grid">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($item = $result->fetch_assoc()): ?>
            <div class="item-card">
                <div class="item-image">
                    <?php if(!empty($item['image'])): ?>
                        <img src="uploads/<?= htmlspecialchars($item['image']) ?>" style="width:100%; height:100%; object-fit:cover;">
                    <?php else: ?>
                        <i class="fa-regular fa-image"></i>
                    <?php endif; ?>
                </div>

                <!-- Gantikan bahagian item-details dengan kod ini -->
                <div class="item-details" style="flex-grow: 1;">
                    <div class="item-name" style="font-weight: bold;"><?= htmlspecialchars($item["item_name"]) ?></div>
                    <div style="font-size:12px; color:#555; margin-top:5px;">
                        
                        <?php if (!empty($item["fullname"])): ?>
                            Requested by: <strong><?= htmlspecialchars($item["fullname"]) ?></strong><br>
                            
                            Requested on: <?= !empty($item['request_date']) ? date('d M Y, h:i A', strtotime($item['request_date'])) : 'N/A' ?><br>
                            
                            <?php if ($item['status'] === 'completed'): ?>
                                Completed on: <?= !empty($item['completed_at']) ? date('d M Y, h:i A', strtotime($item['completed_at'])) : 'N/A' ?>
                            <?php endif; ?>
                            
                        <?php else: ?>
                            <em>No request recorded for this item.</em>
                        <?php endif; ?>
                        
                    </div>
                </div>

                <div class="actions">
                    <?php 
                    // TAMBAHKAN BARIS INI: Define $itemStatus berdasarkan data $item
                    $itemStatus = trim(strtolower($item['status'] ?? '')); 
                    ?>

                    <?php if ($itemStatus === 'approved'): ?>
                        <a href="donationslist.php?complete=<?= $item['donation_id'] ?>" 
                        class="complete-btn" 
                        onclick="return confirm('Adakah anda pasti mahu menandakan barang ini sebagai selesai?')">COMPLETE</a>
                    <?php elseif ($itemStatus === 'completed'): ?>
                        <span class="done-text" style="color: green; font-weight: bold;">DONE</span>
                    <?php endif; ?>

                    <?php if (!empty($item['request_id'])): ?>
                        <button type="button" class="action-btn-link" style="border:none; background:none; cursor:pointer;"
                                onclick="openReportModal(<?= $item['request_id'] ?>, <?= $item['donation_id'] ?>)">
                            <img src="images/report-btn.png" style="width: 25px; height: 25px;" title="Report">
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No items found.</p>
    <?php endif; ?>
</div>

<div id="reportModal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5);">
    <div style="background-color:white; margin:15% auto; padding:20px; width:300px; border-radius:8px; position:relative; color:black;">
        <span onclick="closeReport()" style="float:right; cursor:pointer; font-weight:bold; font-size:20px;">&times;</span>
        <h3 style="margin-top:0;">Report</h3>
        <hr style="margin:10px 0;">
        <p style="cursor:pointer; padding:10px 0; border-bottom:1px solid #eee;" onclick="submitReport('Spam or misleading information.')">Spam or misleading information.</p>
        <p style="cursor:pointer; padding:10px 0;" onclick="submitReport('User who behave inappropriately.')">User who behave inappropriately.</p>
    </div>
</div>

<script>
let currentReqId, currentDonationId;
const currentUserId = <?= json_encode($user_id) ?>; // Ambil dari PHP session

function openReportModal(reqId, donId) {
    currentReqId = reqId;
    currentDonationId = donId;
    document.getElementById('reportModal').style.display = 'block';
}

function closeReport() {
    document.getElementById('reportModal').style.display = 'none';
}

function submitReport(reason) {
    if (!currentReqId) {
        alert("Tiada maklumat permintaan untuk dilaporkan.");
        return;
    }

    const formData = new FormData();
    formData.append('donation_id', currentDonationId);
    formData.append('reason', reason);
    formData.append('user_id', currentUserId);

    fetch('submit_report.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert("Report sent successfully.");
        closeReport();
    })
    .catch(error => console.error('Error:', error));
}
</script>
    <?php include "footer.php"; ?>
    <script src="theme.js"></script>
</body>
</html>