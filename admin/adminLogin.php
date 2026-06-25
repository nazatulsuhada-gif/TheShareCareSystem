<?php
session_start();

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === "admin@sharecare.com" && $password === "admin123") {
        $_SESSION['admin'] = $username;
        header("Location: /thesharecaresystem/admin/adminDashboard.php");
        exit();
    } else {
        $error = "Invalid username or password. Please make sure the uppercase/lowercase letters are correct.";
    }
}
?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: Arial;
}

body{
    background:#fcfae4;
    padding:20px;
}

.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:15px 25px;
    background:#805528;
    border-radius:15px;
}

.logo{
    font-size:20px;
    font-weight:bold;
    color:white;
}

.icon-img{
    width:35px;
    height:35px;
    filter:brightness(0) invert(1);
}

.container{
    max-width:900px;
    width:90%;
    margin:50px auto;
}

.title{
    text-align:center;
    margin-bottom:30px;
}

.title h1{
    font-size:48px;
    color:#5e3b10;
}

.title p{
    color:#555;
    font-size:18px;
}

table{
    width:100%;
    background:#fdfbe0;
    padding:30px;
    border-radius:15px;
    border:1px solid #d6c4a8;
    margin-top:20px;
}

td{
    padding:10px;
    font-size:18px;
    font-weight:bold;
}

input{
    width:100%;
    padding:12px 15px;
    border-radius:10px;
    border:1px solid #d6c4a8;
}

.login-btn{
    display:block;
    width:220px;
    margin:25px 0 10px auto;
    background:#805528;
    color:white;
    border:none;
    padding:15px;
    border-radius:30px;
    font-size:18px;
    font-weight:bold;
    cursor:pointer;
}

.login-btn:hover{
    background:#6b451f;
}

#message{
    text-align:center;
    color:red;
    margin-top:10px;
    font-weight:bold;
}
</style>
</head>

<body>

<?php include("head.php"); ?>

<div class="container">

    <div class="title">
        <h1>WELCOME ADMIN</h1>
        
    </div>

    <form method="POST">

        <table>

            <tr>
                <td>Username</td>
                <td>:</td>
                <td>
                    <input type="text" name="username" placeholder="admin@ShareCare" required>
                </td>
            </tr>

            <tr>
                <td>Password</td>
                <td>:</td>
                <td>
                    <input type="password" name="password" placeholder="Enter password" required>
                </td>
            </tr>

        </table>

        <button type="submit" name="login" class="login-btn">
        REGISTER
        </button>

    </form>

    <?php
    if (isset($error)) {
        echo "<p id='message'>$error</p>";
    }
    ?>

</div>

</body>
</html>