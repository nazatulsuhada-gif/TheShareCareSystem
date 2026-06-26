<?php
session_start();
include("db.php"); 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

$query = "SELECT r.*, u.fullname, d.item_name, d.status, d.image 
          FROM requests r 
          JOIN users u ON r.student_id = u.user_id 
          JOIN donations d ON r.donation_id = d.donation_id 
          WHERE r.student_id = '$student_id'
          ORDER BY r.request_date ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Request</title>

    <link rel="stylesheet" href="donor.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        .page-title {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto 10px;
            font-weight: bold;
            font-size: 18px;
            color: #5e3b10;
        }

        .items-grid {
            width: 90%;
            max-width: 1200px;
            margin: auto;
            background: #fffdf6;
            border: 1px solid #c9b79c;
            border-radius: 12px;
            padding: 25px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .item-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #faf6d1;
            border: 1px solid #c9b79c;
            border-radius: 12px;
            padding: 15px;
            transition: 0.2s;
            cursor: pointer;
        }

        .item-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: #f0edc8;
        }

        .item-left {
            display: flex;
            align-items: center;
            gap: 15px;
            min-width: 0;
        }

        .item-image {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            background: #faf6d1;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #bdbdbd;
            font-size: 24px;
            flex: 0 0 60px;
        }

        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .item-info {
            min-width: 0;
        }

        .item-name {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .item-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .delete-btn-box {
        background-color: #5e3b10; /* Warna coklat gelap sama seperti butang complete */
        color: white;
        padding: 8px 15px;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .delete-btn-box:hover {
        background-color: #7a4d16;
    }

        /* Dark Mode */
        body.dark-mode .page-title { color: #f5f5f5; }
        body.dark-mode .items-grid { background: #2a2a2a; border-color: #444; }
        body.dark-mode .item-card { background: #333; border-color: #555; }
        body.dark-mode .item-name { color: #f5f5f5; }
        body.dark-mode .item-image { background: #2a2a2a; }
        body.dark-mode .delete-btn { color: #f5f5f5; }

        @media (max-width: 768px) {
            .item-card {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            .item-actions {
                align-self: flex-end;
            }
        }
    </style>
</head>

<body>

    <div class="header">
        <a href="request.php">
            <img src="images/back.png" class="icon-img" alt="Back">
        </a>
        <div class="logo">⚪ THE SHARE CARE</div>
        <a href="index.php">
            <img src="images/home.png" class="icon-img" alt="Home">
        </a>
    </div>

    <div class="page-title">My Request</div>

    <div class="items-grid">
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <div class="item-card" id="request-<?php echo $row['request_id']; ?>">
        <a href="reqItemView.php?id=<?php echo $row['donation_id']; ?>" style="text-decoration: none; color: inherit; flex-grow: 1;">
            <div class="item-left">
                <div class="item-image">
                    <?php if (!empty($row['image'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Item">
                    <?php else: ?>
                        <i class="fa-regular fa-image"></i>
                    <?php endif; ?>
                </div>
                <div class="item-info">
                    <div class="item-name"><?php echo htmlspecialchars($row['item_name']); ?></div>
                    <div style="font-size: 12px; color: #666; margin-top: 4px;">
                        Requested: <?php echo date('d M Y, h:i A', strtotime($row['request_date'])); ?>
                        <br>
                        <?php 
                        $reqStatus = strtoupper($row['request_status'] ?? 'N/A');
                        $statusColor = ($reqStatus == 'APPROVED') ? 'green' : (($reqStatus == 'PENDING') ? 'orange' : 'grey');
                        ?>
                        <strong style="color: <?php echo $statusColor; ?>;"><?php echo $reqStatus; ?></strong>
                    </div>
                </div>
            </div>
        </a>
        
        <div class="item-actions"> 
            <button type="button" 
                    class="delete-btn-box" 
                    onclick="padamItem(<?php echo $row['request_id']; ?>)"
                    title="Cancel Request">
                CANCEL
            </button>

            <button type="button" style="border:none; background:none; cursor:pointer;"
                    onclick="openReportModal(<?= $row['request_id'] ?>, <?= $row['donation_id'] ?>)">
                <img src="images/report-btn.png" style="width: 25px; height: 25px;" title="Report">
            </button>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<script>
function padamItem(reqId) {
    if (confirm('Adakah anda pasti untuk membatalkan permintaan ini?')) {
        // Hantar ke process_request.php
        fetch('process_request.php?action=reject&req_id=' + reqId)
        .then(() => {
            // Hilangkan kad dari skrin serta-merta
            const card = document.getElementById('request-' + reqId);
            if (card) card.remove();
        })
        .catch(error => console.error('Error:', error));
    }
}
</script>
    <?php include "footer.php"; ?>

    <script>
        let currentReqId, currentDonationId;
        const currentUserId = <?= json_encode($student_id) ?>;

        function openReportModal(reqId, donId) {
            currentReqId = reqId;
            currentDonationId = donId;
            document.getElementById('reportModal').style.display = 'block';
        }

        function closeReport() {
            document.getElementById('reportModal').style.display = 'none';
        }

        function submitReport(reason) {
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

    <div id="reportModal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5);">
        <div style="background-color:white; margin:15% auto; padding:20px; width:300px; border-radius:8px; position:relative; color:black;">
            <span onclick="closeReport()" style="float:right; cursor:pointer; font-weight:bold; font-size:20px;">&times;</span>
            <h3 style="margin-top:0;">Report</h3>
            <hr style="margin:10px 0;">
            <p style="cursor:pointer; padding:10px 0; border-bottom:1px solid #eee;" onclick="submitReport('Spam or misleading information.')">Spam or misleading information.</p>
            <p style="cursor:pointer; padding:10px 0;" onclick="submitReport('User who behave inappropriately.')">User who behave inappropriately.</p>
        </div>
    </div>
    <script src="theme.js"></script>
</body>
</html>