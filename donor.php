<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("db.php");

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$stmt_check = $conn->prepare("SELECT user_id, fullname, profile_picture, role FROM users WHERE user_id = ?");
$stmt_check->bind_param("i", $_SESSION['user_id']);
$stmt_check->execute();
$userData = $stmt_check->get_result()->fetch_assoc();

if (!$userData) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$role = $userData['role'];
if ($role !== 'staff' && $role !== 'student') {
    header("Location: index.php");
    exit();
}

$user_id = $userData['user_id'];
$fullname = $userData['fullname'];
$profilePic = $userData['profile_picture'] ?? 'images/profile-placeholder.png';

$_SESSION['fullname'] = $fullname;
$_SESSION['role'] = $role; 

$query = "SELECT * FROM donations WHERE user_id = ? AND is_deleted = 0 ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>The Share Care - Donor Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <link rel="stylesheet" href="donor.css"/>
</head>
<body>

<div id="sidebar" class="sidebar">
    <button class="close-btn" onclick="toggleSidebar()"><i class="fa-solid fa-xmark"></i></button>
    <div class="profile-section">

    <div class="profile-picture">
    <img src="<?= htmlspecialchars($profilePic) ?>" class="sidebar-profile-pic" alt="Profile">
</div>

        <h3>Welcome, <span id="username"><?php echo htmlspecialchars($_SESSION['fullname'] ?? 'User'); ?></span></h3>
    </div>
    <ul class="sidebar-links">
        <li><a href="profile.php?from=donor.php"><i class="fa-regular fa-pen-to-square"></i> Edit Profile</a></li>
        <li><a href="donationslist.php"><i class="fa-regular fa-circle-check"></i> My Donation</a></li>
        <li><a href="#" onclick="toggleDarkMode(); return false;"><i class="fa-solid fa-gear"></i> Appearance</a></li>
    </ul>
    <div class="sidebar-footer">
        <button class="logout-btn" onclick="window.location.href='logout.php'">LOG OUT</button>
    </div>
</div>

<header class="header">
    <a href="#" onclick="history.back(); return false;"> 
        <img src="images/back.png" class="icon-img" alt="Back">
    </a>
    <div class="logo">⚪ THE SHARE CARE</div>
    <a href="index.php"><img src="images/home.png" class="icon-img" alt="Home"></a>
</header>

<section class="top-controls">
    <button class="icon-btn" onclick="toggleSidebar()"><i class="fa-solid fa-bars"></i></button>
    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search item">
        <button class="search-btn"><i class="fa-solid fa-magnifying-glass"></i></button>
    </div>
    <select class="category-select" id="category-select" onchange="filterByCategory()">
    <option selected value="all">All</option>
    <option value="1">Books & Stationary</option>
    <option value="2">Clothes</option>
    <option value="3">Electric & Electronics</option>
    </select>
    <a href="upload.php" class="add-btn">+</a>
</section>

<main class="items-grid" id="itemsGrid">
    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($item = mysqli_fetch_assoc($result)): ?>
            <div class="item-card" data-category="<?= htmlspecialchars($item['category_id']) ?>">                
                <div class="item-image" onclick="window.location.href='itemView.php?id=<?= $item['donation_id'] ?>'">
                    <?php if (!empty($item['image'])): ?>
                        <img src="uploads/<?= htmlspecialchars($item['image']) ?>" style="width:100%; height:160px; object-fit:cover;">
                    <?php else: ?>
                        <i class="fa-regular fa-image"></i>
                    <?php endif; ?>
                </div>
                <div class="item-name"><?= htmlspecialchars($item['item_name']) ?></div>
                <div class="item-status"><?= htmlspecialchars($item['status']) ?></div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="grid-column:1/-1; text-align:center; color:#666;">No items uploaded yet.</p>
    <?php endif; ?>
</main>

<?php include "footer.php"; ?>

<script>
    function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle("active");
        document.body.classList.toggle("sidebar-active");
    }

    function toggleDarkMode() {
        document.body.classList.toggle("dark-mode");
    }

    document.getElementById("searchInput").addEventListener("input", function () {
        const query = this.value.toLowerCase();
        document.querySelectorAll(".item-card").forEach(card => {
            const name = card.querySelector(".item-name").textContent.toLowerCase();
            card.style.display = name.includes(query) ? "block" : "none";
        });
    });

    function filterByCategory() {
    const selectedCat = document.getElementById("category-select").value;

    document.querySelectorAll(".item-card").forEach(card => {
        const cat = card.getAttribute("data-category");

        if (selectedCat === "all" || selectedCat === "" || cat === selectedCat) {
            card.style.display = "block";
        } else {
            card.style.display = "none";
        }
    });
}
</script>

</body>
</html>
