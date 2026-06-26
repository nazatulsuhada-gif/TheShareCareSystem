<?php include("db.php");
session_start();

$user_id = $_SESSION['user_id'];

$name = $_POST['name'];
$phone = $_POST['phone'];

if (!empty($_FILES['profileImage']['name'])) {

    $targetDir = "uploads/profile/";
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    $fileExt = pathinfo($_FILES["profileImage"]["name"], PATHINFO_EXTENSION);
    $fileName = $user_id . "_" . time() . "." . $fileExt;
    $targetFile = $targetDir . $fileName;

    move_uploaded_file($_FILES["profileImage"]["tmp_name"], $targetFile);

    $stmt = $conn->prepare("
        UPDATE users 
        SET fullname = ?, phone = ?, profile_picture = ?
        WHERE user_id = ?
    ");
    $stmt->bind_param("sssi", $name, $phone, $targetFile, $user_id);

} else {

    $stmt = $conn->prepare("
        UPDATE users 
        SET fullname = ?, phone = ?
        WHERE user_id = ?
    ");
    $stmt->bind_param("ssi", $name, $phone, $user_id);
}

$stmt->execute();

$_SESSION['success'] = "Profile updated successfully";

header("Location: profile.php");
exit();