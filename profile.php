<?php
session_start();

$returnPage = $_GET['from'] ?? ($_SESSION['return_page'] ?? 'donor.php');
$_SESSION['return_page'] = $returnPage;
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT fullname, email, phone, profile_picture FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();


if ($row = $result->fetch_assoc()) {
    $name = $row['fullname'];
    $email = $row['email'];
    $phone = $row['phone'];
    $profilePic = !empty($row['profile_picture']) ? $row['profile_picture'] : "images/profile-placeholder.png";
} else {
    die("User not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profile</title>

<link rel="stylesheet" href="donor.css">

<style>

.profile-container{
    width:420px;
    margin:30px auto;
    background:#fffdf6;
    border:1px solid #c9b79c;
    padding:20px;
    border-radius:12px;
}

.edit-title{
    font-size:18px;
    font-weight:bold;
    margin-bottom:20px;
}

.profile-picture{
    width:140px;
    height:140px;
    margin:0 auto 20px;
    position:relative;
}

.profile-picture img {
    width: 140px !important;    
    height: 140px !important;   
    border-radius: 50%;         
    object-fit: cover !important; 
    border: 2px solid #805528;
    cursor: pointer;
    display: block;          

.profile-picture input{
    display:none;
}

.form-group{
    display:flex;
    align-items:center;
    margin-bottom:15px;
}

.form-group label{
    width:130px;
}

.form-group input{
    flex:1;
    padding:8px;
    border:1px solid #999;
    border-radius:6px;
}

.gender-group{
    display:flex;
    gap:20px;
    flex:1;
}

.buttons{
    display:flex;
    justify-content:flex-end;
    gap:10px;
    margin-top:20px;
}

.cancel-btn,
.save-btn{
    padding:10px 18px;
    border:none;
    border-radius:20px;
    cursor:pointer;
}

.cancel-btn{
    background:#999;
    color:white;
}

.save-btn{
    background:#805528;
    color:white;
}

body.dark-mode .profile-container{
    background:#2a2a2a;
    border-color:#444;
}

body.dark-mode .form-group input{
    background:#333;
    color:white;
    border-color:#555;
}

body.dark-mode .edit-title,
body.dark-mode label{
    color:white;
}

body.dark-mode .profile-picture img{
    border-color:white;
}

body.dark-mode .save-btn{
    background:#454545 ;
    border-color:#454545 ;
    color:#fff ;
}

body.dark-mode .save-btn:hover{
    background-color:#5a5a5a;
    border-color:#5a5a5a;
}

</style>
</head>


<header class="header">
  <a href="<?php echo $returnPage; ?>" class="header-btn">
    <img src="images/back.png" class="icon-img">
  </a>

  <div class="logo">⚪ THE SHARE CARE</div>

  <a href="index.php" class="header-btn">
    <img src="images/home.png" class="icon-img">
  </a>
</header>

<?php if (isset($_SESSION['success'])): ?>
    <div style="background:#d4edda;padding:10px;margin:10px auto;width:420px;border-radius:8px;">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<div class="profile-container">

    <div class="edit-title">Edit Profile ✎</div>

    <form action="save_profile.php" method="POST" enctype="multipart/form-data">

        <div class="profile-picture">

            <label for="profileImage">
                <img id="previewImage" src="<?= htmlspecialchars($profilePic) ?>">
            </label>

            <input type="file" name="profileImage" id="profileImage" accept="image/*">
        </div>

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($name) ?>">
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($phone) ?>">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="text" value="<?= htmlspecialchars($email) ?>" readonly>
        </div>

        <div class="buttons">
            <button type="button" class="cancel-btn" onclick="history.back()">Cancel</button>
            <button type="submit" class="save-btn">Save</button>
        </div>

    </form>
</div>

<?php include "footer.php"; ?>

<script>
document.getElementById("profileImage").addEventListener("change", function(){
    const file = this.files[0];
    if(file){
        document.getElementById("previewImage").src =
            URL.createObjectURL(file);
    }
});
</script>

<script src="theme.js"></script>

</body>
</html>