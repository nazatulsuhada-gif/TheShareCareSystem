<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['complete'])) {
    $completeId = intval($_GET['complete']);
    
    $conn->begin_transaction();
    
    try {
        $stmt1 = $conn->prepare("UPDATE donations SET status = 'completed' WHERE donation_id = ? AND user_id = ?");
        $stmt1->bind_param("ii", $completeId, $user_id);
        $stmt1->execute();
        
        $stmt2 = $conn->prepare("UPDATE requests SET request_status = 'completed', completed_at = NOW() WHERE donation_id = ?");
        $stmt2->bind_param("i", $completeId);
        $stmt2->execute();
        
        $conn->commit();
        header("Location: donationslist.php");
        exit();
    } catch (Exception $e) {
        $conn->rollback(); 
        echo "Error updating status: " . $e->getMessage();
    }
}

$sql = "SELECT d.*, u.fullname, r.request_date, r.request_id, r.request_status, r.completed_at 
        FROM donations d 
        LEFT JOIN requests r ON d.donation_id = r.donation_id 
        LEFT JOIN users u ON r.student_id = u.user_id 
        WHERE d.user_id = ? 
        AND d.is_deleted = 0 
        AND (d.status = 'approved' OR d.status = 'completed') 
        GROUP BY d.donation_id
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
    padding: 10px 20px; 
    border-radius: 6px; 
    text-decoration: none; 
    font-size: 14px;   
    font-weight: bold; 
    display: inline-block;
    transition: background 0.3s;
}

.complete-btn:hover {
    background: #a06b33; 
}    
.done-text {
    color: green;
    font-weight: bold;
    font-size: 14px;   
    padding: 10px 20px; 
    display: inline-block;
    border: 1px solid transparent; 
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
                        <a href="itemView.php?id=<?= $item['donation_id'] ?>">
                            <img src="uploads/<?= htmlspecialchars($item['image']) ?>" style="width:100%; height:100%; object-fit:cover; display:block;">
                        </a>
                    <?php else: ?>
                        <a href="itemView.php?id=<?= $item['donation_id'] ?>" style="text-decoration:none; color:inherit;">
                            <i class="fa-regular fa-image"></i>
                        </a>
                    <?php endif; ?>
                </div>

                <div class="item-details" style="flex-grow: 1;">
                    <div class="item-name" style="font-weight: bold;"><?= htmlspecialchars($item["item_name"]) ?></div>
                    <div style="font-size:12px; color:#555; margin-top:5px;">
                        <?php if (!empty($item["fullname"])): ?>
                            <?= ($item['status'] === 'completed') ? 'Received by: ' : 'Requested by: ' ?>
                            <strong><?= htmlspecialchars($item["fullname"]) ?></strong><br>
                            
                            <?php if ($item['status'] === 'completed'): ?>
                                <span style="color: green;">Completed on: <?= !empty($item['completed_at']) ? date('d M Y, h:i A', strtotime($item['completed_at'])) : 'N/A' ?></span>
                            <?php else: ?>
                                Requested on: <?= date('d M Y, h:i A', strtotime($item['request_date'])) ?>
                            <?php endif; ?>
                        <?php else: ?>
                            <em>No request recorded.</em>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="actions">
                    <?php $itemStatus = trim(strtolower($item['status'] ?? '')); ?>

                    <?php if ($itemStatus === 'approved'): ?>
                        <a href="donationslist.php?complete=<?= $item['donation_id'] ?>" 
                        class="complete-btn" 
                        onclick="return confirm('Are you sure you want to mark this item as completed?')">COMPLETE</a>
                    
                    <?php elseif ($itemStatus === 'completed'): ?>
                        <span class="done-text">DONE</span>
                    <?php endif; ?>

                    <?php if (!empty($item['request_id'])): ?>
                        <button type="button" class="action-btn-link" onclick="openReportModal(<?= $item['request_id'] ?>, <?= $item['donation_id'] ?>)">
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
const currentUserId = <?= json_encode($user_id) ?>; 

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
        alert("No request information to report.");
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
