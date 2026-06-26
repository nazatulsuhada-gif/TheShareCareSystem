<?php
session_start();
include("../db.php");

if (!isset($_SESSION['admin'])) {
    header("Location: adminLogin.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: adminDonationReview.php");
    exit();
}

$donation_id = (int)$_GET['id'];

if (isset($_POST['update_status'])) {
    $new_status = $_POST['status'];
    
    $stmt = $conn->prepare("UPDATE donations SET status = ? WHERE donation_id = ?");
    $stmt->bind_param("si", $new_status, $donation_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Item status updated to $new_status!'); window.location.href='adminDonationReview.php';</script>";
        exit();
    }
}


$query = "SELECT donations.*, users.fullname 
        FROM donations 
        JOIN users ON donations.user_id = users.user_id 
        WHERE donations.donation_id = ?";
        
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $donation_id);
$stmt->execute();
$result = $stmt->get_result();
$donation = $result->fetch_assoc();

if (!$donation) {
    die("Donation item not found.");
}

$catId = trim($donation['category_id']);
if ($catId == '1' || strtolower($catId) == 'clothing') {
    $catName = 'Clothing';
} elseif ($catId == '2' || strtolower($catId) == 'electric and electronic') {
    $catName = 'Electric and Electronic';
} elseif ($catId == '3' || strtolower($catId) == 'books & stationary') {
    $catName = 'Books & Stationary';
} else {
    $catName = !empty($catId) ? ucfirst($catId) : 'Other';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Donation Details</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        body {
            background-color: #f4ebd9; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .details-container {
            background: white;
            max-width: 650px;
            margin: 40px auto;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
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
            color: #5a4a3a;
            font-size: 24px;
        }
        .btn-back {
            background-color: #7d6b58;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            transition: 0.3s;
        }
        .btn-back:hover {
            background-color: #5a4a3a;
        }
        
        .image-preview-container {
            margin-bottom: 30px;
        }
        .main-item-image {
            max-width: 100%;
            max-height: 280px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border: 3px solid #fff;
        }

        .info-grid {
            text-align: left;
            background: #fafafa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
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
            flex: 0 0 160px;
            font-weight: bold;
            color: #666;
        }
        .info-value {
            flex: 1;
            color: #333;
        }

        .action-form {
            margin-top: 20px;
        }

        .btn-approve { 
            background-color: #8b6e4e; 
            color: white; 
            padding: 12px 28px; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            font-weight: bold; 
            font-size: 15px;
            margin-right: 12px; 
            transition: 0.3s;
        }

        .btn-approve:hover { background-color: #6e553a; }
        .btn-reject { 
            background-color: #ba2d2d; 
            color: white; 
            padding: 12px 28px; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            font-weight: bold; 
            font-size: 15px;
            transition: 0.3s;
        }

        .btn-reject:hover { background-color: #962323; }
        
        .status-badge {
            text-transform: uppercase; 
            font-weight: bold; 
            padding: 3px 8px; 
            border-radius: 4px;
        }
    </style>
</head>

<body>
<?php include("navbar.php"); ?>

<div class="details-container">
    
    <div class="header-section">
        <h2>Donation Item Details</h2>
        <a href="adminDonationReview.php" class="btn-back">⬅ Back to Review</a>
    </div>


    <div class="image-preview-container">
        <img src="../uploads/<?= htmlspecialchars($donation['image']) ?>" class="main-item-image" alt="Item Image">
    </div>

    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Item Name</div>
            <div class="info-value" style="font-weight: 600; color: #111;"><?= htmlspecialchars($donation['item_name']) ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Category</div>
            <div class="info-value"><?= htmlspecialchars($catName) ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Quantity</div>
            <div class="info-value"><?= htmlspecialchars($donation['quantity']) ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Pickup Location</div>
            <div class="info-value"><?= htmlspecialchars($donation['pickup_location']) ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Current Status</div>
            <div class="info-value">
                <?php 
                $statusColor = 'orange';
                if(strtolower($donation['status'])=='approved' || strtolower($donation['status'])=='completed') $statusColor = 'green';
                if(strtolower($donation['status'])=='rejected') $statusColor = 'red';
                ?>
                <span class="status-badge" style="color: <?= $statusColor ?>; background: <?= $statusColor == 'green' ? '#e6f4ea' : ($statusColor == 'red' ? '#fce8e6' : '#fef3e0') ?>;">
                    <?= htmlspecialchars($donation['status']) ?>
                </span>
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">Uploaded By</div>
            <div class="info-value"><?= htmlspecialchars($donation['fullname']) ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Upload Date</div>
            <div class="info-value"><?= date('d F Y', strtotime($donation['created_at'])) ?></div>
        </div>
    </div>

    <form method="POST" class="action-form" onsubmit="return confirm('Are you sure with this action?');">
        <?php if (strtolower($donation['status']) == 'pending'): ?>
            <button type="submit" name="update_status" value="approved" class="btn-approve">Approve Item</button>
            <button type="submit" name="update_status" value="rejected" class="btn-reject">Reject Item</button>
        <?php else: ?>
            <p style="color: #777; font-style: italic; background: #f9f9f9; padding: 12px; border-radius: 8px;">
                This item has already been processed as <b style="color: <?= $statusColor ?>; text-transform: uppercase;"><?= htmlspecialchars($donation['status']) ?></b>.
            </p>
        <?php endif; ?>
    </form>
</div>

</body>
</html>