<?php
session_start();
include("db.php");

// 1. SEMAK LOG MASUK
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2. DEBUGGING (Hapus jika sudah pasti berjalan lancar)
// Jika Siti masih boleh masuk, buang komen baris bawah ini untuk lihat apa isi role dia
// var_dump($_SESSION['role']); exit(); 

// 3. SEKAT AKSES ALUMNI/STAFF
// Jika user bukan 'student', jangan biarkan dia kekal di halaman ini.
// Kita gunakan redirection terus supaya tiada masalah "refresh" alert berulang.

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    // Log keluar jika sesi tidak sah atau role salah untuk keselamatan
    // Pilihan: Jika anda mahu user terus ke tempat yang betul berdasarkan role mereka:
    
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: donor.php"); // Atau ke halaman utama mereka
    }
    exit();
}
$user_id = $_SESSION['user_id']; 

// 4. Query untuk gambar profil
$userQuery = "SELECT profile_picture FROM users WHERE user_id = ?";
$stmt_user = $conn->prepare($userQuery);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$userResult = $stmt_user->get_result()->fetch_assoc();
$profilePic = $userResult['profile_picture'] ?? 'images/profile-placeholder.png';

// 5. Kira jumlah request bulanan (HANYA YANG APPROVED)
$current_month = date('m');
$current_year = date('Y');

// TAMBAHKAN: AND request_status = 'approved'
$query_limit = "SELECT COUNT(*) as total_requests 
                FROM requests 
                WHERE student_id = ? 
                AND request_status = 'approved' 
                AND MONTH(request_date) = ? 
                AND YEAR(request_date) = ?";

$stmt_limit = $conn->prepare($query_limit);
$stmt_limit->bind_param("iii", $user_id, $current_month, $current_year);
$stmt_limit->execute();
$result_limit = $stmt_limit->get_result()->fetch_assoc();

$total_requests = $result_limit['total_requests'];
$remaining_requests = max(0, 3 - $total_requests);

$total_requests = $result_limit['total_requests'];
$remaining_requests = max(0, 3 - $total_requests);

$query = "SELECT d.*, u.fullname as donor_name, c.category_name, 
          (SELECT COUNT(*) FROM favourites f WHERE f.donation_id = d.donation_id AND f.student_id = ?) as is_fav
          FROM donations d 
          LEFT JOIN users u ON d.user_id = u.user_id 
          LEFT JOIN categories c ON d.category_id = c.category_id 
          WHERE d.status = 'approved' 
          AND d.is_deleted = 0 
          AND d.quantity > 0 
          AND d.status != 'completed' 
          ORDER BY d.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$approved_items = [];
while ($row = $result->fetch_assoc()) {
    $approved_items[] = $row;
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>The Share Care - Browse Items</title>


  <link
    rel="stylesheet"
    href="request.css" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />



</head>

<script src="theme.js"></script>

<body>
  <div class="header">
    <a href="student.php">
      <img src="images/back.png" class="icon-img" alt="Back" />
    </a>

    <div class="logo-title">
      <h1>⚪ THE SHARE CARE</h1>
    </div>

    <a href="index.php">
      <button class="header-btn">
        <i class="fa-solid fa-house"></i>
      </button>
    </a>
  </div>

  <div id="sidebar" class="sidebar">
    <button class="close-btn" onclick="toggleSidebar()">
      <i class="fa-solid fa-xmark"></i>
    </button>

    <div class="profile-section">
        <div class="profile-picture">
            <img src="<?= htmlspecialchars($profilePic) ?>" class="sidebar-profile-pic" alt="Profile" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
        </div>
        <h3>Welcome, <span id="username"><?php echo htmlspecialchars($_SESSION['fullname'] ?? 'User'); ?></span></h3>
    </div>

    <div class="quota-info" style="margin-top: 10px; font-size: 13px; color: #555; text-align: center;">
        <p>Monthly Request: <strong><?= $total_requests ?> / 3</strong></p>
        <div style="background: #e0e0e0; height: 6px; width: 80%; margin: 5px auto; border-radius: 3px;">
            <div style="background: #805528; height: 100%; width: <?= ($total_requests / 3) * 100 ?>%; border-radius: 3px;"></div>
        </div>
        <small>Remaining: <?= $remaining_requests ?></small>
    </div>
    
    

    <ul class="sidebar-links">
      <li>
        <a href="profile.php?from=request.php"><i class="fa-regular fa-pen-to-square"></i> Edit Profile</a>
      </li>
      <li>
        <a href="requestlist.php"><i class="fa-regular fa-circle-check"></i> My Request</a>
      </li>
      <li>
        <a href="#" onclick="toggleTheme(); return false;"><i class="fa-solid fa-gear"></i> Appearance</a>
      </li>
    </ul>

    <div class="sidebar-footer">
      <button class="logout-btn" onclick="window.location.href='logout.php'">
        LOG OUT
      </button>
    </div>
  </div>

  <div class="top-controls">
    <button class="icon-btn" onclick="toggleSidebar()">
      <i class="fa-solid fa-bars"></i>
    </button>

    <div class="search-container">
      <input type="text" id="searchInput" placeholder="Search item" />
      <button class="search-btn" onclick="dummyClick()">
        <i class="fa-solid fa-magnifying-glass"></i>
      </button>
    </div>

    <select class="category-select" id="category-select" onchange="filterByCategory()">
      <option selected value="all">All</option>
      <option value="1">Books &amp; Stationary</option>
      <option value="2">Clothes</option>
      <option value="3">Electric &amp; Electronics</option>
    </select>

    <a href="favourite.php" class="favourite-btn">
      <i class="fa-regular fa-heart"></i>
    </a>
  </div>

 <div class="items-grid">
    <?php if (!empty($approved_items)): ?>
        <?php foreach ($approved_items as $item): ?>
            <?php $itemId = urlencode($item['donation_id'] ?? ''); ?>
            
            <div class="item-card" 
                 data-category="<?= htmlspecialchars($item['category_id'] ?? '0') ?>" 
                 onclick="window.location.href='reqItemView.php?id=<?= $itemId ?>'">
                
                <div class="item-image">
                    <?php if (!empty($item['image'])): ?>
                        <img src="uploads/<?= htmlspecialchars($item['image']) ?>" alt="Item Image">
                    <?php else: ?>
                        <div style="height:100%; display:flex; align-items:center; justify-content:center;"><i class="fa-regular fa-image"></i></div>
                    <?php endif; ?>
                </div>
                
                <div class="item-content">
                    <div class="item-name" style="font-weight: bold; font-size: 15px; margin-bottom: 5px;">
                        <?= htmlspecialchars($item['item_name']) ?>
                    </div>
                    
                    <div style="font-size: 12px; color: #888; margin-bottom: 10px;">
                        <?= htmlspecialchars($item['category_name']) ?>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; font-size: 12px; border-top: 1px solid #eee; padding-top: 10px;">
                        <?php if ($item['quantity'] > 0): ?>
                            <span>📦 <?= htmlspecialchars($item['quantity']) ?> unit</span>
                        <?php else: ?>
                            <span style="color: red;">Out of stock</span>
                        <?php endif; ?>
                        <span style="color: #805528;"><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($item['pickup_location']) ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="grid-column:1/-1; text-align:center; color:#666;">No approved items available.</p>
    <?php endif; ?>
</div>

  

 <script>
  function toggleSidebar() {
    document.getElementById("sidebar").classList.toggle("active");
    document.body.classList.toggle("sidebar-active");
  }

  function filterByCategory() {
    const selectedCat = document.getElementById("category-select").value;
    const cards = document.querySelectorAll(".item-card");
    
    cards.forEach(card => {
        // Pastikan setiap item-card di HTML anda ada attribute data-category
        const cat = card.getAttribute("data-category"); 
        if (selectedCat === "all" || cat === selectedCat) {
            card.style.display = "block";
        } else {
            card.style.display = "none";
        }
    });
  }

  // SEARCH FUNCTIONALITY
  const searchInput = document.getElementById("searchInput");
  searchInput.addEventListener("input", function() {
    const query = this.value.toLowerCase().trim();
    document.querySelectorAll(".item-card").forEach((card) => {
      const itemName = card.querySelector(".item-name").textContent.toLowerCase();
      card.style.display = itemName.includes(query) ? "block" : "none";
    });
  });

  
</script>



  <!-- FOOTER -->
  <?php include "footer.php"; ?>

</body>

</html>