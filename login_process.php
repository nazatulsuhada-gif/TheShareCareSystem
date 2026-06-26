<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $role_pilihan = $_POST['role']; 

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        if ($row['role'] !== $role_pilihan) {
            echo "<script>alert('This account is registered as " . ucfirst($row['role']) . ". You cannot log in as " . ucfirst($role_pilihan) . "'); window.history.back();</script>";
            exit();
        }

        if (password_verify($password, $row['password'])) {
            session_regenerate_id(true); 
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['role'] = $row['role']; 
            $_SESSION['fullname'] = $row['fullname'];
            
            if ($row['role'] === 'student') {
                header("Location: student.php");
            } elseif ($row['role'] === 'alumni' || $row['role'] === 'staff') {
                header("Location: donor.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            echo "<script>alert('Incorrect password!'); window.history.back();</script>";
            exit();
        }
    } else {
        // Mesej jika akaun tidak dijumpai
        echo "<script>alert('Account not found!'); window.history.back();</script>";
        exit();
    }
}
?>