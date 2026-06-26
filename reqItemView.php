<?php
session_start();
include("db.php");

$id = $_GET['id'] ?? '';
$item = null;
$my_status = ''; 
$already_requested = false;
$total_approved_this_month = 0; // Inisialisasi awal

if (!empty($id) && isset($_SESSION['user_id'])) {
    $student_id = $_SESSION['user_id'];

    // 1. Query untuk ambil maklumat item
    $stmt = $conn->prepare("SELECT d.*, u.phone as donor_phone, u.fullname as donor_name, c.category_name, 
                            (SELECT COUNT(*) FROM favourites f WHERE f.donation_id = d.donation_id AND f.student_id = ?) as is_fav
                            FROM donations d 
                            JOIN users u ON d.user_id = u.user_id 
                            LEFT JOIN categories c ON d.category_id = c.category_id 
                            WHERE d.donation_id = ?");
    $stmt->bind_param("ii", $student_id, $id);
    $stmt->execute();
    $item = $stmt->get_result()->fetch_assoc();

    if ($item) {
        // 2. Semak had 3 request sebulan
        $stmt_limit = $conn->prepare("SELECT COUNT(*) as total FROM requests 
                              WHERE student_id = ? 
                              AND request_status = 'approved' 
                              AND MONTH(request_date) = MONTH(CURRENT_DATE()) 
                              AND YEAR(request_date) = YEAR(CURRENT_DATE())");
        $stmt_limit->bind_param("i", $student_id);
        $stmt_limit->execute();
        $limit_data = $stmt_limit->get_result()->fetch_assoc();
        $total_approved_this_month = $limit_data['total'];

        // 3. Auto-delete request pending jika sudah capai had 3/3
        if ($total_approved_this_month >= 3) {
            $stmt_del = $conn->prepare("DELETE FROM requests WHERE student_id = ? AND request_status = 'pending'");
            $stmt_del->bind_param("i", $student_id);
            $stmt_del->execute();
        }

        // 4. Semak status request pelajar untuk item ini
        $req_stmt = $conn->prepare("SELECT request_status FROM requests WHERE donation_id = ? AND student_id = ?");
        $req_stmt->bind_param("ii", $id, $student_id);
        $req_stmt->execute();
        $req_data = $req_stmt->get_result()->fetch_assoc();
        
        if ($req_data) {
            $my_status = $req_data['request_status'];
            $already_requested = true;
        }
    }
}

if (!$item) {
    echo "Item tidak dijumpai.";
    exit();
}

// Fallback values
$item_name = $item['item_name'] ?? 'Unknown Item';
$description = $item['description'] ?? 'No description available';
$location = $item['pickup_location'] ?? 'Not specified';
$image = $item['image'] ?? '';
$quantity = $item['quantity'] ?? '0';
$category_name = $item['category_name'] ?? 'Not specified';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Details</title>

    <link rel="stylesheet" href="donor.css">

    <style>
        /* ======================
BASE LAYOUT (MATCH SYSTEM)
====================== */

        body {
            background: #fcfae4;
            padding: 20px;
            font-family: Arial;
        }

        /* HEADER (consistent) */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 25px;
            background: #805528;
            border-radius: 15px;
        }

        .logo {
            font-size: 20px;
            font-weight: bold;
            color: white;
        }

        .icon-img {
            width: 35px;
            height: 35px;
            filter: brightness(0) invert(1);
        }

        /* ======================
CONTAINER (LIKE itemView.php)
====================== */

        .container {
            max-width: 900px;
            width: 90%;
            margin: 40px auto;
        }

        /* ======================
CARD BOX (STANDARDIZED)
====================== */

        .card-box {
            background: #fdfbe0;
            border: 1px solid #d6c4a8;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        /* ======================
IMAGE
====================== */

        .item-preview {
            width: 160px;
            height: 160px;
            margin: 0 auto 20px;
            border-radius: 12px;
            overflow: hidden;
            background: #faf6d1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .item-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .placeholder-icon {
            font-size: 60px;
            color: #bdbdbd;
        }

        /* ======================
TITLE
====================== */

        .item-title {
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 25px;
            color: #5e3b10;
        }

        /* ======================
DETAILS (CONSISTENT FORM STYLE)
====================== */

        .form-group {
            display: flex;
            margin-bottom: 15px;
        }

        .form-label {
            width: 180px;
            font-weight: bold;
            color: #5e3b10;
        }

        .colon {
            width: 20px;
            text-align: center;
            font-weight: bold;
            color: #5e3b10;
        }

        .form-value {
            flex: 1;
            padding: 10px 12px;
            border: 1px solid #d6c4a8;
            border-radius: 8px;
            background: #faf6d1;
        }

        /* ======================
ACTIONS
====================== */

        .actions {
            margin-top: 25px;
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .request-btn {
            background: #000;
            color: #fff;
            border: none;
            border-radius: 30px;
            padding: 12px 35px;
            font-size: 18px;
            cursor: pointer;
        }

        .favourite-btn {
            width: 55px;
            height: 55px;
            border: none;
            border-radius: 50%;
            background: #000;
            color: #fff;
            font-size: 28px;
            cursor: pointer;
        }

        .favourite-btn.active {
            color: #ff4d6d;
        }

        /* ======================
DARK MODE (CONSISTENT STYLE)
====================== */

        body.dark-mode {
            background: #1e1e1e;
            color: #f5f5f5;
        }

        body.dark-mode .header {
            background: #2b2b2b;
        }

        body.dark-mode .card-box {
            background: #2a2a2a;
            border-color: #444;
        }

        body.dark-mode .item-title,
        body.dark-mode .form-label,
        body.dark-mode .colon {
            color: #f5f5f5;
        }

        body.dark-mode .form-value {
            background: #333;
            color: #fff;
            border-color: #555;
        }

        body.dark-mode .item-preview {
            background: #444;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="header">
        <a href="#" onclick="history.back(); return false;">
            <img src="images/back.png" class="icon-img" alt="Back">
        </a>
        
        <div class="logo">⚪ THE SHARE CARE</div>

        <a href="index.php">
            <img src="images/home.png" class="icon-img" alt="Home">
        </a>
    </div>

   <div class="container">
        <div class="card-box">
            <div class="item-preview">
                <?php if (!empty($image)): ?>
                    <img src="uploads/<?php echo htmlspecialchars($image); ?>">
                <?php else: ?>
                    <span class="placeholder-icon">🖼</span>
                <?php endif; ?>
            </div>

            <div class="item-title">
                <?php echo htmlspecialchars($item_name); ?>
            </div>

            <?php if ($my_status == 'approved'): ?>
                <div style="margin: 15px auto; padding: 15px; background: #e8f5e9; border: 1px solid #c8e6c9; border-radius: 8px; width: 90%; text-align: center;">
                    <p style="color: #2e7d32; font-weight: bold; margin-bottom: 5px;">
                        <i class="fa-solid fa-check-circle"></i> Request Approved!
                    </p>
                    <p style="margin: 5px 0;">Donor: <?php echo htmlspecialchars($item['donor_name']); ?></p>
                    <p style="margin: 5px 0;">
                        Contact: <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $item['donor_phone']); ?>" target="_blank" style="color: #007bff; text-decoration: none;">
                            <?php echo htmlspecialchars($item['donor_phone']); ?> <i class="fa-brands fa-whatsapp"></i>
                        </a>
                    </p>
                </div>
            <?php elseif ($my_status == 'pending'): ?>
                <div style="margin: 15px auto; padding: 10px; background: #fff3cd; border: 1px solid #ffeeba; border-radius: 8px; width: 90%; text-align: center; color: #856404;">
                    <i class="fa-solid fa-clock"></i> Status: Waiting for donor approval.
                </div>
            <?php endif; ?>

            <div class="form-group">
                <div class="form-label">Quantity</div>
                <div class="colon">:</div>
                <div class="form-value"><?php echo htmlspecialchars($quantity); ?></div>
            </div>

            <div class="form-group">
                <div class="form-label">Category</div>
                <div class="colon">:</div>
                <div class="form-value"><?php echo htmlspecialchars($category_name); ?></div>
            </div>

            <div class="form-group">
                <div class="form-label">Description</div>
                <div class="colon">:</div>
                <div class="form-value"><?php echo htmlspecialchars($description); ?></div>
            </div>

            <div class="form-group">
                <div class="form-label">Location</div>
                <div class="colon">:</div>
                <div class="form-value"><?php echo htmlspecialchars($location); ?></div>
            </div>

            <div class="actions">
                <?php if ($my_status == 'approved'): ?>
                    <button class="request-btn" style="background-color: #28a745; color: white; cursor: default;" disabled>
                        APPROVED
                    </button>
                
                <?php elseif ($already_requested): ?>
                    <button class="request-btn" style="background-color: #ccc; cursor: not-allowed;" disabled>
                        PENDING APPROVAL
                    </button>

                <?php elseif ($total_approved_this_month >= 3): ?>
                    <button class="request-btn" style="background-color: #dc3545; color: white; cursor: not-allowed;" disabled>
                        HAD BULANAN DICAPAI (3/3)
                    </button>

                <?php else: ?>
                    <button class="request-btn" id="requestBtn">REQUEST</button>
                <?php endif; ?>

                <?php if ($my_status != 'approved'): ?>
                    <button class="favourite-btn <?php echo ($item['is_fav'] > 0) ? 'active' : ''; ?>" id="favouriteBtn">
                        <span id="favIcon"><?php echo ($item['is_fav'] > 0) ? '❤' : '♡'; ?></span>
                    </button>
                <?php endif; ?>
            </div>
        </div> 
    </div>
    <!-- FOOTER -->
<?php include "footer.php"; ?>

    <script src="theme.js"></script>

    <script>
    // 1. Logik untuk Favourite Button (dengan semakan jika butang wujud)
    const favBtn = document.getElementById("favouriteBtn");
    
    if (favBtn) {
        favBtn.addEventListener("click", function() {
            const btn = this;
            const favIcon = document.getElementById("favIcon");
            const donation_id = <?php echo json_encode($id); ?>; // Guna json_encode untuk keselamatan

            fetch("toggle_favourite.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "donation_id=" + donation_id
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'added') {
                    btn.classList.add("active");
                    favIcon.innerText = '❤';
                    alert("Item successfully added to your favourites!"); 
                } else if (data.status === 'removed') {
                    btn.classList.remove("active");
                    favIcon.innerText = '♡';
                    alert("Item removed from your favourites.");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Something went wrong, please try again.");
            });
        });
    }

    // 2. Logik untuk Request Button
    const reqBtn = document.getElementById("requestBtn");
    
    if (reqBtn) {
        reqBtn.addEventListener("click", function() {
            if(confirm("Adakah anda pasti mahu membuat permintaan untuk item ini?")) {
                // Disable butang selepas ditekan
                this.disabled = true;
                this.innerText = "REQUESTED";
                this.style.backgroundColor = "#ccc";
                this.style.cursor = "not-allowed";
                
                // Hantar ke fail proses
                window.location.href = "submit_request.php?donation_id=<?php echo $id; ?>";
            }
        });
    }
</script>

</body>

</html>