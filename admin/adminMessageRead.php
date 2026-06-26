<?php
session_start();
include("../db.php");


if (!isset($_SESSION['admin'])) {
    header("Location: adminLogin.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: adminMessageList.php");
    exit();
}

$id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT * FROM contact_messages WHERE message_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$message = $result->fetch_assoc();

if (!$message) {
    die("Message not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Message</title>
    <link rel="stylesheet" href="admin.css">
    <style>
       
        .message-card {
            background: white !important;
            max-width: 650px;
            margin: 40px auto;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: none !important;
        }
        
        .header-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 15px;
        }
        
        .header-section h2 {
            margin: 0;
            color: #805528;
            font-size: 24px;
        }

        
        .info-grid {
            text-align: left;
            background: #fafafa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            border: 1px solid #eaeaea;
        }
        
        .info-row {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            flex: 0 0 140px;
            font-weight: bold;
            color: #666;
        }
        
        .info-value {
            flex: 1;
            color: #333;
        }

        .message-box-title {
            color: #805528;
            font-size: 18px;
            margin: 20px 0 10px 0;
            font-weight: bold;
            text-align: left;
        }

        .message-content-box {
            background-color: #fdfbf7;
            border-left: 4px solid #805528; 
            padding: 20px;
            border-radius: 4px 12px 12px 4px;
            min-height: 120px;
            line-height: 1.6;
            color: #2c2c2c;
            border-top: 1px solid #eaeaea;
            border-right: 1px solid #eaeaea;
            border-bottom: 1px solid #eaeaea;
            white-space: pre-line;
            text-align: left;
        }

        .back-btn {
            display: inline-block;
            margin-top: 25px;
            padding: 10px 20px;
            background: #805528;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s;
        }
        
        .back-btn:hover {
            background: #6b451f;
        }
    </style>
</head>

<body>

    <?php include("navbar.php"); ?>

    <div class="container">

        <div class="message-card">

            <div class="header-section">
                <h2>Contact Message</h2>
                <a href="adminMessageList.php" class="back-btn" style="margin-top: 0; padding: 8px 16px; font-size: 14px;">⬅ Back to Messages</a>
            </div>

            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Name</div>
                    <div class="info-value" style="font-weight: 600; color: #111;"><?= htmlspecialchars($message['name']) ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email</div>
                    <div class="info-value">
                        <a href="mailto:<?= htmlspecialchars($message['email']) ?>" style="color: #805528; text-decoration: underline; font-weight: 600;">
                            <?= htmlspecialchars($message['email']) ?>
                        </a>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Phone</div>
                    <div class="info-value"><?= htmlspecialchars($message['phone']) ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Date Received</div>
                    <div class="info-value"><?= date('d F Y, g:i a', strtotime($message['created_at'])) ?></div>
                </div>
            </div>

            <div class="message-box-title">Message Body</div>
            <div class="message-content-box">
                <?= nl2br(htmlspecialchars($message['message'])) ?>
            </div>

        </div>

    </div>
    
</body>
</html>