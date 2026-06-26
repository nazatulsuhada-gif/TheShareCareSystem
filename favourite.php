<?php
// 1. MESTI mulakan sesi (Hanya perlu sekali sahaja)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Sertakan fail sambungan database
include("db.php"); 

// 3. Pastikan student sudah login
if (!isset($_SESSION['user_id'])) {
    die("Sila login untuk melihat senarai kegemaran.");
}
$student_id = $_SESSION['user_id']; 

// 4. Query untuk ambil data dari database (Hanya perlu sekali sahaja)
$sql = "SELECT d.donation_id AS id, d.item_name, d.image, d.status 
        FROM donations d
        JOIN favourites f ON d.donation_id = f.donation_id
        WHERE f.student_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$favourites = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favourite</title>

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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .heart-icon {
            color: #805528;
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
        }

        .item-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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

            display: flex;
            justify-content: center;
            align-items: center;

            font-size: 24px;
            color: #bdbdbd;
            flex: 0 0 60px;
        }

        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .item-name {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .item-actions {
            flex-shrink: 0;
        }

        /* Efek untuk item yang sudah diambil/completed (Claimed) */
        .item-card.item-unavailable {
            opacity: 0.6;
            filter: grayscale(80%);
            background: #f0f0f0;
        }

        /* KECUALIKAN butang delete daripada kesan pointer-events: none */
        .item-card.item-unavailable .item-left {
            pointer-events: none; /* Hanya halang klik pada bahagian maklumat item */
        }

        /* Pastikan butang delete sentiasa boleh ditekan */
        .item-actions {
            pointer-events: auto; 
        }

        .delete-btn {
            border: none;
            background: none;
            cursor: pointer;
            font-size: 20px;
            color: #333;
            padding: 4px;
        }

        .delete-btn:hover {
            transform: scale(1.1);
        }

        /* DARK MODE */

        body.dark-mode .page-title {
            color: #f5f5f5;
        }

        body.dark-mode .items-grid {
            background: #2a2a2a;
            border-color: #444;
        }

        body.dark-mode .item-card {
            background: #333;
            border-color: #555;
        }

        body.dark-mode .item-name {
            color: #f5f5f5;
        }

        body.dark-mode .item-image {
            background: #2a2a2a;
        }

        body.dark-mode .delete-btn {
            color: #f5f5f5;
        }

    </style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    <a href="request.php">
        <img src="images/back.png" class="icon-img" alt="Back">
    </a>

    <div class="logo">⚪ THE SHARE CARE</div>

    <a href="index.php">
        <img src="images/home.png" class="icon-img" alt="Home">
    </a>
</div>

<!-- TITLE -->
<div class="page-title">
    <span>Favourite</span>
    
</div>

<!-- GRID -->
<div class="items-grid">

<?php if (empty($favourites)): ?>
    <p style="text-align:center;">Tiada item dalam senarai kegemaran.</p>
<?php else: ?>
    <?php foreach ($favourites as $item): 
        $isClaimed = ($item['status'] === 'completed');
        $cardClass = $isClaimed ? "item-card item-unavailable" : "item-card";
    ?>
        <div class="<?php echo $cardClass; ?>" id="fav-<?php echo $item['id']; ?>">
            
            <div class="item-left" onclick="<?php echo $isClaimed ? '' : "window.location.href='reqItemView.php?id=" . urlencode($item['id']) . "'"; ?>" style="cursor: pointer;">
                <div class="item-image">
                    <?php if (!empty($item['image'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="">
                    <?php else: ?>
                        <i class="fa-regular fa-image"></i>
                    <?php endif; ?>
                </div>
                <div class="item-name">
                    <?php echo htmlspecialchars($item['item_name']); ?>
                    <?php if ($isClaimed) echo "<span class='status-label'>(Not Available)</span>"; ?>
                </div>
            </div>

            <div class="item-actions">
                <button class="delete-btn" data-id="<?php echo $item['id']; ?>" type="button" title="Remove Favourite">
                    🗑
                </button>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

</div>

<?php include "footer.php"; ?>

<script>
document.querySelectorAll(".delete-btn").forEach(btn => {
    btn.addEventListener("click", function (e) {
        e.stopPropagation(); // Mencegah fungsi klik pada kad item
        
        const id = this.dataset.id;
        const confirmDelete = confirm("Remove this item from favourites?");

        if (!confirmDelete) return;

        // Menghantar data ke toggle_favourite.php
        fetch("toggle_favourite.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "id=" + id
        })
        .then(response => response.json())
        .then(data => {
            // Hanya buang kad jika server berjaya memadamkan data (status: removed)
            if (data.status === 'removed') {
                const card = document.getElementById("fav-" + id);
                if (card) {
                    card.remove();
                }
            } else {
                alert("Failed to remove item. Please try again.");
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
</script>
<script src="theme.js"></script>

</body>
</html>