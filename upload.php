<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("db.php");
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $quantity = (int)$_POST['quantity'];
    if ($quantity <= 0) {
        die("The quantity must be at least 1.");
    }
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $status = 'pending';
    if (!isset($_FILES['product_image']) || $_FILES['product_image']['error'] !== UPLOAD_ERR_OK) {
        echo "<script>alert('Error: Please upload a picture of your donation item!'); window.history.back();</script>";
        exit();
    }
    $imageName = time() . "_" . basename($_FILES['product_image']['name']);
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $targetDir . $imageName)) {
        $sql = "INSERT INTO donations (user_id, item_name, quantity, category_id, pickup_location, description, image, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ississss", $user_id, $item_name, $quantity, $category, $location, $description, $imageName, $status);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: donor.php?success=1");
            exit();
        } else {
            echo "Error: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Failed to save the image file.'); window.history.back();</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Product - The Share Care</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }
        body {
            background: #fcfae4;
            padding: 20px;
            overflow-x: hidden;
        }
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
            object-fit: contain;
            cursor: pointer;
            filter: brightness(0) invert(1);
        }
        .sub-header-bar {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            font-size: 22px;
            font-weight: bold;
            color: #5e3b10;
            gap: 10px;
            border-bottom: 2px solid #805528;
            margin-bottom: 20px;
        }
        .sub-header-bar img {
            width: 20px;
            height: 20px;
        }
        .container {
            max-width: 1200px;
            width: 90%;
            margin: 50px auto;
        }
        .form-layout {
            display: flex;
            gap: 50px;
            align-items: center;
        }
        .image-upload-wrapper {
            width: 350px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .image-placeholder {
            width: 350px;
            height: 350px;
            background: #faf6d1;
            border: 2px dashed #805528;
            border-radius: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            overflow: hidden;
        }
        .camera-img {
            width: 120px;
            height: 120px;
            object-fit: contain;
        }
        #imageInput {
            display: none;
        }
        .details-wrapper {
            flex: 1;
        }
        .instruction-text {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .card-box {
            background: #fdfbe0;
            border: 1px solid #d6c4a8;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }
        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 18px;
        }
        .form-label {
            width: 180px;
            font-size: 18px;
            font-weight: bold;
        }
        .input-text, .select-field, .textarea-field {
            width: 100%;
            padding: 12px 15px;
            font-size: 16px;
            border: 1px solid #d6c4a8;
            border-radius: 10px;
            background: #faf6d1;
        }
        .textarea-field {
            resize: none;
            height: 120px;
        }
        .quantity-counter {
            display: flex;
            align-items: center;
            overflow: hidden;
            border-radius: 10px;
        }
        .counter-btn {
            width: 40px;
            height: 40px;
            border: none;
            background: #805528;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }
        .counter-value {
            width: 60px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            background: #faf6d1;
            border-top: 1px solid #d6c4a8;
            border-bottom: 1px solid #d6c4a8;
        }
        .footer-actions {
            display: flex;
            justify-content: flex-end;
        }
        .submit-btn {
            background: #805528;
            color: white;
            border: none;
            border-radius: 30px;
            padding: 15px 50px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
        }
        .submit-btn:hover {
            background: #6b451f;
        }
        /* Dark Mode */
        body.dark-mode { background: #1e1e1e; color: #f5f5f5; }
        body.dark-mode .header { background: #2b2b2b; }
        body.dark-mode .sub-header-bar { color: #f5f5f5; border-bottom: 2px solid #555; }
        body.dark-mode .card-box { background: #2a2a2a; border-color: #444; }
        body.dark-mode .input-text, body.dark-mode .select-field, body.dark-mode .textarea-field { background: #333; color: #fff; border-color: #555; }
        body.dark-mode .submit-btn { background: #444; color: #fff; border: 1px solid #666; }
        body.dark-mode .submit-btn:hover { background: #555; }
        body.dark-mode .image-placeholder { background: #2a2a2a; border-color: #555; }
        body.dark-mode .counter-btn { background: #444; color: #fff; border: 1px solid #666; }
        body.dark-mode .counter-value { background: #2a2a2a; color: #fff; border-color: #555; }
        
        @media(max-width:900px) {
            .form-layout { flex-direction: column; }
            .image-upload-wrapper { width: 100%; }
            .image-placeholder { margin: auto; }
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="donor.php"><img src="images/back.png" class="icon-img"></a>
        <div class="logo">⚪ THE SHARE CARE</div>
        <a href="index.php"><img src="images/home.png" class="icon-img"></a>
    </div>
    <div class="sub-header-bar">
        Upload Products <img src="images/upload.png">
    </div>
    <div class="container">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-layout">
                <div class="image-upload-wrapper">
                    <label for="imageInput" class="image-placeholder" id="dropZone">
                        <img src="images/camera.png" class="camera-img" id="cameraPreview">
                        <input type="file" id="imageInput" name="product_image" accept="image/*">
                    </label>
                </div>
                <div class="details-wrapper">
                    <div class="instruction-text">Please fill in the details:</div>
                    <div class="card-box">
                        <div class="form-group">
                            <label class="form-label">Item Name</label>
                            <input type="text" name="item_name" class="input-text" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Quantity</label>
                            <div class="quantity-counter">
                                <button type="button" class="counter-btn" onclick="decrementCounter()">-</button>
                                <div class="counter-value" id="quantityDisplay">0</div>
                                <button type="button" class="counter-btn" onclick="incrementCounter()">+</button>
                            </div>
                            <input type="hidden" id="quantityInput" name="quantity" value="0">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Category</label>
                            <select name="category" class="select-field" required>
                                <option value="">Choose Category</option>
                                <?php
                                $cat_query = mysqli_query($conn, "SELECT * FROM categories");
                                while ($cat = mysqli_fetch_assoc($cat_query)) {
                                    echo '<option value="' . $cat['category_id'] . '">' . $cat['category_name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Pick-up Location</label>
                            <select name="location" class="select-field" required>
                                <option value="">Choose Location</option>
                                <option>FTMK</option>
                                <option>FTKM</option>
                                <option>FTKIP</option>
                                <option>FTKE</option>
                                <option>FTKEK</option>
                                <option>PPP</option>
                                <option>PPB</option>
                                <option>Kafeteria Pelajar 1</option>
                                <option>Kafeteria Pelajar 2</option>
                                <option>Masjid Sayyidina Abu Bakar</option>
                                <option>Dewan Canselor</option>
                                <option>Dewan Kuliah</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-box">
                        <div class="form-label">Description</div>
                        <textarea name="description" class="textarea-field" placeholder="Describe the item..." required></textarea>
                    </div>
                    <div class="footer-actions">
                        <button type="submit" class="submit-btn">SUBMIT</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php include "footer.php"; ?>
    <script>
        let currentQuantity = 0;
        const quantityDisplay = document.getElementById("quantityDisplay");
        const quantityInput = document.getElementById("quantityInput");

        function incrementCounter() {
            currentQuantity++;
            quantityDisplay.textContent = currentQuantity;
            quantityInput.value = currentQuantity;
        }
        function decrementCounter() {
            if (currentQuantity > 0) {
                currentQuantity--;
                quantityDisplay.textContent = currentQuantity;
                quantityInput.value = currentQuantity;
            }
        }
        window.addEventListener("DOMContentLoaded", () => {
            const theme = localStorage.getItem("theme");
            if (theme === "dark") {
                document.body.classList.add("dark-mode");
            }
        });
        const imageInput = document.getElementById("imageInput");
        const dropZone = document.getElementById("dropZone");
        imageInput.addEventListener("change", function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    dropZone.style.backgroundImage = `url('${event.target.result}')`;
                    dropZone.style.backgroundSize = "cover";
                    dropZone.style.backgroundPosition = "center";
                    document.getElementById("cameraPreview").style.display = "none";
                }
                reader.readAsDataURL(file);
            }
        });
        document.querySelector("form").addEventListener("submit", function(e) {
            const qty = parseInt(quantityInput.value);
            if (qty <= 0) {
                e.preventDefault();
                alert("Quantity must be at least 1");
            }
        });
    </script>
</body>
</html>
