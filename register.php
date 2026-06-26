<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register - The Share Care</title>

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
      color: #fff;
    }

    .icon-img {
      width: 35px;
      height: 35px;
      object-fit: contain;
      cursor: pointer;
      filter: brightness(0) invert(1);
    }

    .container {
      max-width: 1200px;
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
      background: #faf6d1;
    }

    .register-btn {
      display: block;
      width: 220px;
      margin: 25px 0 10px auto;
      background: #805528;
      color: white;
      text-decoration: none;
      text-align: center;
      padding: 15px;
      border-radius: 30px;
      font-size: 18px;
      font-weight: bold;
      transition: 0.3s;
    }

    .register-btn:hover {
      background: #6b451f;
    }

    .login {
      text-align: center;
      font-size: 16px;
      margin-top: 20px;
    }

    .login a {
      color: #805528;
      text-decoration: none;
      font-weight: bold;
    }

    .login a:hover {
      text-decoration: underline;
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

    .register-btn {
      display: block;
      width: 220px;
      margin: 25px 0 10px auto;
      background: #805528;
      color: white;
      border: none;
      cursor: pointer;
      text-align: center;
      padding: 15px;
      border-radius: 30px;
      font-size: 18px;
      font-weight: bold;
      transition: .3s;
    }

    @media (max-width: 992px) {

      .title h1 {
        font-size: 52px;
      }

      .title p {
        font-size: 20px;
      }

      .role-icon {
        width: 230px;
        height: 230px;
      }

      .role-icon::before {
        width: 70px;
        height: 70px;
        top: 40px;
      }

      .role-icon::after {
        width: 140px;
        height: 75px;
        bottom: 28px;
      }
    }

    @media (max-width: 768px) {

      .header {
        padding: 15px;
      }

      .container {
        width: 95%;
      }

      .title {
        margin-bottom: 40px;
      }

      .title h1 {
        font-size: 42px;
      }

      .title p {
        font-size: 18px;
      }

      .roles {
        gap: 30px;
      }

      .role-icon {
        width: 180px;
        height: 180px;
      }

      .role-icon::before {
        width: 55px;
        height: 55px;
        top: 30px;
        border-width: 2px;
      }

      .role-icon::after {
        width: 110px;
        height: 60px;
        bottom: 22px;
        border-width: 2px;
      }

      .role-label {
        font-size: 18px;
      }

      
    }

    @media (max-width: 480px) {

      body {
        padding: 10px;
      }

      .logo {
        font-size: 18px;
      }

      .icon-img {
        width: 30px;
        height: 30px;
      }

      .title h1 {
        font-size: 34px;
      }

      .title p {
        font-size: 16px;
      }

      .role-icon {
        width: 150px;
        height: 150px;
      }

      .role-icon::before {
        width: 45px;
        height: 45px;
        top: 25px;
      }

      .role-icon::after {
        width: 90px;
        height: 50px;
        bottom: 18px;
      }

      .role-label {
        font-size: 16px;
      }

      
    }
  </style>
</head>

<body>
  <div class="header">
    <a href="role.php">
      <img src="images/back.png" class="icon-img" alt="Back">
    </a>

    <div class="logo">⚪ THE SHARE CARE</div>

    <a href="index.php">
      <img src="images/home.png" class="icon-img" alt="Home" />
    </a>
  </div>

  <div class="container">
    <div class="title">
      <h1>REGISTER</h1>
      <p>Please enter your details:</p>
    </div>


    <form action="register_process.php" method="POST" onsubmit="return validateRegister()">
      <input type="hidden" name="role" value="<?php echo htmlspecialchars($_GET['role'] ?? ''); ?>">    

      <input 
      type="hidden" 
      name="role" 
      value="<?php echo $_GET['role'] ?? ''; ?>"> 


      <table>

        <tr>

          <td width="40%">Name</td>

          <td class="colon">:</td>

          <td>

            <input
            type="text"
            name="name"
            placeholder="Full Name (as in matric card)"
            required />

          </td>

        </tr>

        <tr>

          <td>E-mail UTeM</td>

          <td class="colon">:</td>

          <td>
            <input 
              type="email" 
              id="email" 
              name="email" 
              placeholder="Enter your email" 
              required />
          </td>

        </tr>

        <tr>

          <td>Mobile Number</td>

          <td class="colon">:</td>

          <td>
            <input 
            type="text" 
            id="number"
            name="number" 
            placeholder="0123456789"
            required />
          </td>
        </tr>

        <tr>
          <td>Password</td>

          <td class="colon">:</td>

          <td>

            <div class="password-box">

              <input

              type="password"

              id="password"

              name="password"

              placeholder="Enter password"

              required />

              <img 
              src="images/eye-close.png" 
              class="eye-icon" 
              id="eye1" />

            </div>

          </td>

        </tr>

        <tr>

          <td>Re-enter Password</td>

          <td class="colon">:</td>

          <td>

            <div class="password-box">

              <input

              type="password"

              id="confirmPassword"

              name="confirmPassword"

              placeholder="Re-enter password"

              required />

              <img 
              src="images/eye-close.png" 
              class="eye-icon" 
              id="eye2" />

            </div>

          </td>

        </tr>

      </table>

      <button type="submit" class="register-btn">
        REGISTER
      </button>

    </form>

    <div class="login">

      Already have an account?

      <a href="login.php">
        Log in
      </a>

    </div>

</div>

<?php include "footer.php"; ?>

<script>
window.onload = function() {
    let role = "<?php echo htmlspecialchars($_GET['role'] ?? ''); ?>";
    let emailField = document.getElementById("email");

    if (role === "staff") {
        emailField.placeholder = "azilah@utem.edu.my";
    } else if (role === "student") {
        emailField.placeholder = "B012345678@student.utem.edu.my";
    } else if (role === "alumni") {
        emailField.placeholder = "melo123@gmail.com";
    }
};

function validateRegister() {
    let role = "<?php echo htmlspecialchars($_GET['role'] ?? ''); ?>";
    let email = document.getElementById("email").value.toLowerCase(); 
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirmPassword").value;
    let number = document.getElementById("number").value;

    if (password !== confirmPassword) {
        alert("Passwords do not match!");
        return false;
    }

    let phonePattern = /^[0-9]{10,11}$/;
    if (!phonePattern.test(number)) {
        alert("Please enter a valid mobile number (10-11 digits)!");
        return false;
    }

    if (role === "staff") {
        if (!email.endsWith("@utem.edu.my")) {
            alert("Staff must use @utem.edu.my email!");
            return false;
        }
    } else if (role === "student") {
        if (!email.endsWith("@student.utem.edu.my")) {
            alert("Students must use @student.utem.edu.my email!");
            return false;
        }
    } else if (role === "alumni") {
        if (!email.endsWith("@gmail.com")) {
            alert("Alumni must use @gmail.com email!");
            return false;
        }
    }

    return true;
}

const password = document.getElementById("password");

const eye1 = document.getElementById("eye1");

eye1.onclick = function(){

    if(password.type === "password"){
        password.type = "text";
        eye1.src = "images/eye-open.png";
    }
    else{
        password.type = "password";
        eye1.src = "images/eye-close.png";
    }
};

const confirmPassword = document.getElementById("confirmPassword");

const eye2 = document.getElementById("eye2");

eye2.onclick = function(){

    if(confirmPassword.type === "password"){

        confirmPassword.type = "text";

        eye2.src = "images/eye-open.png";

    }

    else{

        confirmPassword.type = "password";

        eye2.src = "images/eye-close.png";

    }

};

</script>