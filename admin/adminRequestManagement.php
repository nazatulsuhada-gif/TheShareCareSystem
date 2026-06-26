<?php
session_start();
include("../db.php");

if (!isset($_SESSION['admin'])) {
    header("Location: adminLogin.php");
    exit();
}


$statusQuery = "SELECT request_status, COUNT(*) as total FROM requests GROUP BY request_status";
$statusResult = mysqli_query($conn, $statusQuery);

$pendingCount = 0;
$approvedCount = 0;
$rejectedCount = 0;

while($sRow = mysqli_fetch_assoc($statusResult)) {
    if(strtolower($sRow['request_status']) == 'pending') $pendingCount = $sRow['total'];
    if(strtolower($sRow['request_status']) == 'approved') $approvedCount = $sRow['total'];
    if(strtolower($sRow['request_status']) == 'rejected') $rejectedCount = $sRow['total'];
}


$search = "";
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

$safeSearch = mysqli_real_escape_string($conn, $search);


$sql = "SELECT 
            requests.request_id,
            users.fullname AS student_name,
            donations.item_name,
            requests.request_status
        FROM requests
        JOIN users ON requests.student_id = users.user_id
        JOIN donations ON requests.donation_id = donations.donation_id
        WHERE users.fullname LIKE '%$safeSearch%'
        OR donations.item_name LIKE '%$safeSearch%'
        OR requests.request_status LIKE '%$safeSearch%'
        ORDER BY requests.request_date DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Request Management</title>
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
    </style>
</head>

<body>

<?php include("navbar.php"); ?>

<h1>Request Analytics</h1>

<div class="charts-flex-container">
    <div class="analytics-card">
        <h3>Request Status Distribution</h3>
        <div class="chart-box">
            <canvas id="requestPieChart"></canvas>
        </div>
    </div>
</div>

<hr style="width:85%; border:1px solid #d6c4a8; margin:40px auto;">

<h1>Request Management</h1>
<center>
    <div class="search-box-container" style="display: flex; justify-content: center; align-items: center; gap: 10px;">
        <div class="search-form-wrapper">
            <?php include("search.php"); ?>
        </div>
        <?php if (!empty($search)): ?>
            <a href="adminRequestManagement.php" style="text-decoration: none;">
                <button type="button" style="background-color: #805528; color: white; padding: 10px 18px; border-radius: 10px; border: none; cursor: pointer; font-weight: bold; font-size: 14px;">
                    ❌ Clear Search
                </button>
            </a>
        <?php endif; ?>
    </div>

<br>

<table align="center" border="1" cellpadding="10">

<tr>
    <th>No.</th>
    <th>Student Name</th>
    <th>Requested Item</th>
    <th>Status</th>
</tr>

<?php 
if (mysqli_num_rows($result) > 0) {
    $bil = 1;
    while ($row = mysqli_fetch_assoc($result)) { 
?>
    <tr>
        <td><?= $bil++ ?></td>
        <td><?= htmlspecialchars($row['student_name']) ?></td>
        <td><?= htmlspecialchars($row['item_name']) ?></td>
        <td style="text-transform: uppercase; font-weight: bold; color: 
            <?= strtolower($row['request_status'])=='approved' ? 'green' : (strtolower($row['request_status'])=='rejected' ? 'red' : 'orange') ?>;">
            <?= htmlspecialchars($row['request_status']) ?>
        </td>
    </tr>
<?php 
    } 
} else {
    echo "<tr><td colspan='4' style='text-align:center;'>No requests found.</td></tr>";
}
?>

</table>
</center>

<script>
    const ctxRequest = document.getElementById('requestPieChart').getContext('2d');
    const pCount = <?php echo $pendingCount; ?>;
    const aCount = <?php echo $approvedCount; ?>;
    const rCount = <?php echo $rejectedCount; ?>;
    const totalRequest = pCount + aCount + rCount;

    new Chart(ctxRequest, {
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
                            let pct = totalRequest > 0 ? ((val / totalRequest) * 100).toFixed(1) : 0;
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