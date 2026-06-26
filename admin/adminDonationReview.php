<?php
session_start();
include("../db.php");

if (!isset($_SESSION['admin'])) {
    header("Location: adminLogin.php");
    exit();
}

if (isset($_GET['action']) && isset($_GET['id'])) {

    $id = (int)$_GET['id'];
    $action = $_GET['action'];

    if ($action === "approve") {
        $status = "approved";
    } elseif ($action === "reject") {
        $status = "rejected";
    } else {
        die("Invalid action");
    }

    $stmt = $conn->prepare("UPDATE donations SET status = ? WHERE donation_id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();

    header("Location: adminDonationReview.php");
    exit();
}

$statusQuery = "SELECT status, COUNT(*) as total FROM donations GROUP BY status";
$statusResult = mysqli_query($conn, $statusQuery);

$pendingCount = 0;
$approvedCount = 0;
$rejectedCount = 0;

while($sRow = mysqli_fetch_assoc($statusResult)) {
    if(strtolower($sRow['status']) == 'pending') $pendingCount = $sRow['total'];
    if(strtolower($sRow['status']) == 'approved') $approvedCount = $sRow['total'];
    if(strtolower($sRow['status']) == 'rejected') $rejectedCount = $sRow['total'];
}


$categoryQuery = "SELECT category_id, COUNT(*) as total FROM donations GROUP BY category_id";
$categoryResult = mysqli_query($conn, $categoryQuery);

$categories = [];
$categoryTotals = [];

while($cRow = mysqli_fetch_assoc($categoryResult)) {
    $catId = trim($cRow['category_id']);
    
    if ($catId == '1' || strtolower($catId) == 'clothing') {
        $catName = 'Clothing';
    } elseif ($catId == '2' || strtolower($catId) == 'electric and electronic') {
        $catName = 'Electric and Electronic';
    } elseif ($catId == '3' || strtolower($catId) == 'books & stationary') {
        $catName = 'Books & Stationary';
    } else {
        $catName = !empty($catId) ? ucfirst($catId) : 'Other';
    }

    $categories[] = $catName;
    $categoryTotals[] = $cRow['total'];
}


$search = "";
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}


$safeSearch = mysqli_real_escape_string($conn, $search);

$sql = "SELECT donations.donation_id, donations.item_name, donations.status, donations.image, 
        donations.category_id, donations.quantity, donations.pickup_location, users.fullname
        FROM donations
        JOIN users ON donations.user_id = users.user_id
        WHERE donations.item_name LIKE '%$safeSearch%'
        OR users.fullname LIKE '%$safeSearch%'
        OR donations.status LIKE '%$safeSearch%'
        OR donations.pickup_location LIKE '%$safeSearch%'
        OR (
            CASE 
                WHEN donations.category_id = '1' THEN 'clothing'
                WHEN donations.category_id = '2' THEN 'electric and electronic'
                WHEN donations.category_id = '3' THEN 'books & stationary'
                ELSE LOWER(donations.category_id)
            END LIKE '%$safeSearch%'
        )
        ORDER BY donations.created_at DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Donation Item Review</title>
    <link rel="stylesheet" href="admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .charts-flex-container {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
            margin: 20px auto 40px auto;
            max-width: 1000px;
        }
        .analytics-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 2px 8px gray;
            text-align: center;
            width: 380px;
        }
        .chart-box {
            width: 250px;
            height: 250px;
            margin: 15px auto;
        }
        .search-box-container {
            margin: 20px auto;
        }
        .search-form-wrapper form {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .item-thumbnail {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>
<?php include("navbar.php"); ?>

<h1>Donation Analytics</h1>

<div class="charts-flex-container">
    
    <div class="analytics-card">
        <h3>Donation Status Distribution</h3>
        <div class="chart-box">
            <canvas id="statusPieChart"></canvas>
        </div>
    </div>

    <div class="analytics-card">
        <h3>Items by Category</h3>
        <div class="chart-box">
            <canvas id="categoryPieChart"></canvas>
        </div>
    </div>

</div>

<hr style="width:85%; border:1px solid #d6c4a8; margin:40px auto;">

<h1>Donation Item Review</h1>

<center>
    <div class="search-box-container" style="display: flex; justify-content: center; align-items: center; gap: 10px;">
        <div class="search-form-wrapper">
            <?php include("search.php"); ?>
        </div>
        <?php if (!empty($search)): ?>
            <a href="adminDonationReview.php" style="text-decoration: none;">
                <button type="button" style="background-color:#805528 color: white; padding: 10px 18px; border-radius: 10px; border: none; cursor: pointer; font-weight: bold; font-size: 14px;">
                    ❌ Clear Search
                </button>
            </a>
        <?php endif; ?>
    </div>

<br>

<table align="center" border="1" cellpadding="10">
<tr>
    <th>No.</th>
    <th>Image</th>
    <th>Donor Name</th>
    <th>Item</th>
    <th>Category</th>
    <th>Quantity</th>
    <th>Location</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php 
if(mysqli_num_rows($result) > 0) { 
    $bil = 1; 
    while ($row = mysqli_fetch_assoc($result)) { 
        
        $tableCatId = trim($row['category_id']);
        if ($tableCatId == '1' || strtolower($tableCatId) == 'clothing') {
            $tableCatName = 'Clothing';
        } elseif ($tableCatId == '2' || strtolower($tableCatId) == 'electric and electronic') {
            $tableCatName = 'Electric and Electronic';
        } elseif ($tableCatId == '3' || strtolower($tableCatId) == 'books & stationary') {
            $tableCatName = 'Books & Stationary';
        } else {
            $tableCatName = !empty($tableCatId) ? ucfirst($tableCatId) : 'Other';
        }
    ?>
    <tr>
        <td>
            <?= $bil++ ?>
        </td>
        <td style="text-align: center;">
            <?php if(!empty($row['image'])): ?>
                <a href="adminDonationDetails.php?id=<?= $row['donation_id'] ?>">
                    <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" class="item-thumbnail" alt="Item Image" style="cursor: pointer;">
                </a>
                
            <?php else: ?>
                <span style="color: gray; font-size: 12px;">No Image</span>
            <?php endif; ?>
            
        </td>
        <td><?= htmlspecialchars($row['fullname']) ?></td>
        <td>
            <a href="adminDonationDetails.php?id=<?= $row['donation_id'] ?>" style="text-decoration: none; color: black;">
                <?= htmlspecialchars($row['item_name']) ?>
            </a>
        </td>
        <td><?= htmlspecialchars($tableCatName) ?></td>
        <td style="text-align: center;"><?= htmlspecialchars($row['quantity']) ?></td>
        <td><?= htmlspecialchars($row['pickup_location']) ?></td>
        <td style="text-transform: uppercase; font-weight: bold; color: 
            <?= strtolower($row['status'])=='approved' ? 'green' : (strtolower($row['status'])=='rejected' ? 'red' : 'orange') ?>;">
            <?= htmlspecialchars($row['status']) ?>
        </td>
        <td>
            <a href="?action=approve&id=<?= $row['donation_id'] ?>" onclick="return confirm('Approve this item?')">Approve</a>
            |
            <a href="?action=reject&id=<?= $row['donation_id'] ?>" onclick="return confirm('Reject this item?')">Reject</a>
        </td>
    </tr>

    <?php } 
} else {
    echo "<tr><td colspan='9' style='text-align:center;'>No donation items found.</td></tr>";
} ?>
</table>

</center>

<script>
    const ctxStatus = document.getElementById('statusPieChart').getContext('2d');
    const pCount = <?php echo $pendingCount; ?>;
    const aCount = <?php echo $approvedCount; ?>;
    const rCount = <?php echo $rejectedCount; ?>;
    const totalStatus = pCount + aCount + rCount;

    new Chart(ctxStatus, {
        type: 'pie',
        data: {
            labels: ['Pending', 'Approved', 'Rejected'],
            datasets: [{
                data: [pCount, aCount, rCount],
                backgroundColor: ['#ff9f43', '#28c76f', '#ea5455'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let val = context.raw || 0;
                            let pct = totalStatus > 0 ? ((val / totalStatus) * 100).toFixed(1) : 0;
                            return `${context.label}: ${val} (${pct}%)`;
                        }
                    }
                },
                legend: { position: 'bottom' }
            }
        }
    });


    const ctxCategory = document.getElementById('categoryPieChart').getContext('2d');
    const categoryLabels = <?php echo json_encode($categories); ?>;
    const categoryData = <?php echo json_encode($categoryTotals); ?>;
    const totalCat = categoryData.reduce((a, b) => a + b, 0);

    new Chart(ctxCategory, {
        type: 'pie',
        data: {
            labels: categoryLabels,
            datasets: [{
                data: categoryData,
                backgroundColor: ['#7367f0', '#00cfe8', '#ff9f43', '#1ea7ff', '#ff6b6b', '#9b5de5'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let val = context.raw || 0;
                            let pct = totalCat > 0 ? ((val / totalCat) * 100).toFixed(1) : 0;
                            return `${context.label}: ${val} (${pct}%)`;
                        }
                    }
                },
                legend: { position: 'bottom' }
            }
        }
    });
</script>

</body>
</html>