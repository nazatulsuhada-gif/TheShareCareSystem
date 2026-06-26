<?php
include("db.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['number']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    if($password !== $confirmPassword) die("Error: Passwords do not match.");
    
    // Semakan e-mel
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if(mysqli_num_rows($check) > 0) die("Error: Email already registered.");

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (fullname, email, phone, password, role, profile_picture) VALUES ('$fullname', '$email', '$phone', '$hashedPassword', '$role', 'default.png')";

    if(mysqli_query($conn, $sql)){
        echo "<script>alert('Registration successful!'); window.location.href='login.php?role=$role';</script>";
    }
}
?>