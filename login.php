<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'student') {
                header("Location: index.php");
            } else {
                header("Location: donor.php");
            }
            exit();
        } else {
            echo "<script>alert('Incorrect email or password.');</script>";
        }
    } else {
        echo "<script>alert('Incorrect email or password.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - The Share Care</title>

    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial;
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

      .container {
        max-width: 900px;
        width: 90%;
        margin: 50px auto;
      }

      .title {
        text-align: center;
        margin-bottom: 30px;
      }

      .title h1 {
        font-size: 48px;
        margin-bottom: 10px;
        color: #5e3b10;
      }

      .title p {
        font-size: 18px;
        color: #555;
      }

      #roleDisplay {
        margin-top: 10px;
        font-size: 18px;
        font-weight: bold;
      }

      table {
        width: 100%;
        background: #fdfbe0;
        padding: 30px;
        border-radius: 15px;
        border: 1px solid #d6c4a8;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
      }

      td {
        padding: 10px;
        font-size: 18px;
        font-weight: bold;
      }

      .colon {
        width: 20px;
        text-align: center;
      }

      input {
        width: 100%;
        padding: 12px 15px;
        font-size: 16px;
        border: 1px solid #d6c4a8;
        border-radius: 10px;
        background: #fffdf8;
      }
      .login-btn {
        display: block;
        width: 220px;
        margin: 25px 0 10px auto;
        background: #805528;
        color: white;
        border: none;
        text-decoration: none;
        text-align: center;
        padding: 15px;
        border-radius: 30px;
        font-size: 18px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
      }

      .login-btn:hover {
        background: #6b451f;
      }

      .register {
        text-align: center;
        font-size: 16px;
        margin-top: 20px;
      }

      .register a {
        color: #805528;
        font-weight: bold;
        text-decoration: none;
      }

      .register a:hover {
        text-decoration: underline;
      }

      #message {
        text-align: center;
        color: #b22222;
        margin-top: 15px;
        font-weight: bold;
      }

      #roleDisplay {
        margin-top: 10px;
        font-size: 18px;
        font-weight: bold;
        color: #805528;
      }

      .back-btn {
        background: none;
        border: none;
        outline: none;
        padding: 0;
        cursor: pointer;
      }

      .password-box {
        position: relative;
      }

      .eye-icon {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 22px;
        height: 22px;
        cursor: pointer;
      }

@media (max-width: 992px) {

    .container{
        width:95%;
    }

    .title h1{
        font-size:42px;
    }
}

@media (max-width: 768px) {

    .header{
        padding:12px 15px;
    }

    .logo{
        font-size:16px;
    }

    .icon-img{
        width:28px;
        height:28px;
    }

    .container{
        width:100%;
        margin:30px auto;
    }

    .title{
        margin-bottom:20px;
    }

    .title h1{
        font-size:34px;
    }

    .title p,
    #roleDisplay{
        font-size:16px;
    }

    table{
        padding:15px;
    }

    td{
        display:block;
        width:100%;
        padding:6px 0;
        font-size:16px;
    }

    .colon{
        display:none;
    }

    input{
        font-size:15px;
        padding:10px 12px;
    }

    .login-btn{
        width:100%;
        margin:20px 0 10px;
    }

    .register{
        font-size:15px;
    }

    .eye-icon{
        width:20px;
        height:20px;
        right:10px;
    }
}

@media (max-width: 480px) {

    body{
        padding:10px;
    }

    .title h1{
        font-size:28px;
    }

    .title p,
    #roleDisplay{
        font-size:14px;
    }

    td{
        font-size:15px;
    }

    .login-btn{
        font-size:16px;
        padding:12px;
    }
}
    </style>
  </head>

  <?php
if (isset($_GET['registered'])) {
    echo "
    <script>
        alert('Registration successful! Please log in.');
    </script>
    ";
}
?>

  <body>
    <div class="header">
      <a href="role.php">
        <img src="images/back.png" class="icon-img" alt="Back" />
      </a>

      <div class="logo">⚪ THE SHARE CARE</div>

      <a href="index.php">
        <img src="images/home.png" class="icon-img" alt="Home" />
      </a>
    </div>

   <div class="container">
  <div class="title">
    <h1>WELCOME</h1>
    <p>Please log in to continue.</p>
    
    <p id="roleDisplay">
        <?php 
        $role = isset($_GET['role']) ? $_GET['role'] : ''; 
        
        if (!empty($role)) {
            echo "Logging in as: " . ucfirst(htmlspecialchars($role));
        } else {
            echo "Logging in as: User";
        }
        ?>
    </p>
</div>

  <form action="login_process.php" method="POST">
    <input type="hidden" name="role" value="<?php echo htmlspecialchars($_GET['role'] ?? ''); ?>"> 
    <table>
      <tr>
        <td width="40%">E-mail</td>
        <td class="colon">:</td>
        <td>
          <input type="email" name="email" id="username" placeholder="d012345678@student.utem.edu.my" required />
        </td>
      </tr>

      <tr>
        <td>Password</td>
        <td class="colon">:</td>
        <td>
          <div class="password-box">
            <input type="password" name="password" id="password" placeholder="Enter password" required />
            <img src="images/eye-close.png" id="togglePassword" class="eye-icon" alt="Show Password" />
          </div>
        </td>
      </tr>
    </table>

    <button type="submit" class="login-btn">
      LOG IN
    </button>
  </form>

  <div class="register">
    New to Share Care?
    <a href="register.php?role=<?php echo $_GET['role'] ?? ''; ?>">
      Register
    </a>   
  </div>

  <p id="message"></p>
</div>

<?php include "footer.php"; ?>

<script>
  window.onload = function() {
      let role = "<?php echo htmlspecialchars($_GET['role'] ?? ''); ?>";
      let emailField = document.getElementById("username"); 

      if (role === "staff") {
          emailField.placeholder = "azilah@utem.edu.my";
      } else if (role === "student") {
          emailField.placeholder = "B012345678@student.utem.edu.my";
      } else if (role === "alumni") {
          emailField.placeholder = "melo123@gmail.com";
      }
  };
  const passwordInput = document.getElementById("password");
  const togglePassword = document.getElementById("togglePassword");

  togglePassword.addEventListener("click", function () {
    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      togglePassword.src = "images/eye-open.png";
      togglePassword.alt = "Hide Password";
    } else {
      passwordInput.type = "password";
      togglePassword.src = "images/eye-close.png";
      togglePassword.alt = "Show Password";
    }
  });
</script>