<?php
session_start();
include("../db.php");

if(!isset($_SESSION['admin'])) {
    header("Location: /thesharecaresystem/admin/adminLogin.php");
    exit();
}

$roleQuery = "SELECT role, COUNT(*) as total FROM users GROUP BY role";
$roleResult = mysqli_query($conn, $roleQuery);

$studentCount = 0;
$staffCount = 0;

while($row = mysqli_fetch_assoc($roleResult)) {
    if($row['role'] == 'student') $studentCount = $row['total'];
    if($row['role'] == 'staff') $staffCount = $row['total'];
}
$totalUsers = $studentCount + $staffCount;


$search = "";
if(isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

$safeSearch = mysqli_real_escape_string($conn, $search);

$sql = "SELECT * FROM users 
        WHERE fullname LIKE '%$safeSearch%' 
        OR email LIKE '%$safeSearch%' 
        OR role LIKE '%$safeSearch%'
        ORDER BY created_at DESC";

$result = mysqli_query($conn, $sql);

if(!$result){
    die("QUERY ERROR: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Management & Analytics</title>
    <link rel="stylesheet" href="admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .analytics-section {
            background: white;
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 2px 8px gray;
            text-align: center;
        }
        .chart-container {
            width: 300px;
            height: 300px;
            margin: auto;
        }
        .search-box-container {
            margin: 30px auto 10px auto;
            text-align: center;
        }
        .search-box-container form {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
        .user-table {
            width: 90%;
            margin: 20px auto;
        }
        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>

<body>

<?php include("navbar.php"); ?>

<h1>TOTAL USER ANALYTICS</h1>

<div class="analytics-section">
    <h3>Total Registered Users: <?php echo $totalUsers; ?></h3>
    <div class="chart-container">
        <canvas id="userPieChart"></canvas>
    </div>
</div>

<hr style="width:90%; border:1px solid #d6c4a8; margin:40px auto;">

<h1>USER LIST DATA</h1>

<div class="search-box-container">
    <div style="display: flex; justify-content: center; align-items: center; gap: 10px;">
        <div>
            <?php include("search.php"); ?>
        </div>
        <?php if (!empty($search)): ?>
            <a href="adminUserManagement.php" style="text-decoration: none;">
                <button type="button" style="background-color:#805528; color: white; padding: 10px 18px; border-radius: 10px; border: none; cursor: pointer; font-weight: bold; font-size: 14px;">
                    ❌ Clear Search
                </button>
            </a>
        <?php endif; ?>
    </div>
</div>

<center>
    <table border="1" class="user-table" cellpadding="10">
        <tr>
            <th>NO.</th>
            <th>PROFILE PICTURE</th>
            <th>FULL NAME</th>
            <th>EMAIL</th>
            <th>PHONE</th>
            <th>ROLE</th>
            <th>JOINED DATE</th>
        </tr>

        <?php 
        $no = 1;
        if(mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)){ 
        ?>
            <tr>
                <td style="text-align: center;"><?php echo $no++; ?></td>
                <td style="text-align: center;">
                    <?php 
                    $profilePath = "../uploads/profile/" . $row['profile_picture']; 
                    
                    if(!empty($row['profile_picture']) && file_exists($profilePath)): ?>
                        <a href="<?php echo $profilePath; ?>" target="_blank">
                            <img src="<?php echo $profilePath; ?>" class="profile-img" alt="Profile">
                        </a>
                    
                    <?php else: ?>
                        <a href="#" onclick="alert('This user has not uploaded a profile picture.'); return false;" style="text-decoration: none;">
                            <span style="font-size:24px;">👤</span>
                        </a>
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td style="text-align: center;"><?php echo htmlspecialchars($row['phone']); ?></td>
                <td style="text-transform: uppercase; font-weight: bold; text-align: center;">
                    <?php echo htmlspecialchars($row['role']); ?>
                </td>
                <td style="text-align: center;"><?php echo date('d-m-Y', strtotime($row['created_at'])); ?></td>
            </tr>
        <?php 
            } 
        } else {
            echo "<tr><td colspan='7' style='text-align:center;'>No users found.</td></tr>";
        }
        ?>
    </table>
</center>

<script>
    const ctx = document.getElementById('userPieChart').getContext('2d');
    
    const studentData = <?php echo $studentCount; ?>;
    const staffData = <?php echo $staffCount; ?>;
    const total = studentData + staffData + alumniData;

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Student', 'Staff'],
            datasets: [{
                data: [studentData, staffData],
                backgroundColor: [
                    '#ffcc00', 
                    '#36a2eb' 
                ],
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
                            let value = context.raw || 0;
                            let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return `${context.label}: ${value} (${percentage}%)`;
                        }
                    }
                },
                legend: {
                    position: 'right',
                    labels: {
                        font: { size: 14 }
                    }
                }
            }
        }
    });
</script>

</body>
</html>
