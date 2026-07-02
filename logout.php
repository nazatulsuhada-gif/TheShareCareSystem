<?php
session_start();

$isAdmin = isset($_SESSION['admin']);

$_SESSION = array();
session_destroy();

if ($isAdmin) {
    header("Location: adminLogin.php");
} else {
    header("Location: login.php");
}
exit();
?>
