<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id   = $_POST['donation_id'];
    $name = $_POST['item_name'];
    $qty  = (int)$_POST['quantity']; 
    $desc = $_POST['description'];
    $loc  = $_POST['location'];

    if (empty($id) || $qty <= 0) {
        die("Invalid data provided.");
    }

    $sql = "UPDATE donations 
            SET item_name = ?, 
                quantity = ?, 
                description = ?, 
                pickup_location = ? 
            WHERE donation_id = ? AND status = 'pending'";

    $stmt = mysqli_prepare($conn, $sql);
    
    mysqli_stmt_bind_param($stmt, "sissi", $name, $qty, $desc, $loc, $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: itemView.php?id=$id&msg=success");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
} else {
    header("Location: donor.php");
    exit();
}
?>
