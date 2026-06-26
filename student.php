<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!doctype html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student - The Share Care</title>

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
        max-width: 1200px;
        width: 90%;
        margin: 50px auto;
      }

      .title {
        text-align: center;
        margin-bottom: 60px;
      }

      .title h1 {
        font-size: 72px;
        margin-bottom: 10px;
        color: #5e3b10;
      }

      .title p {
        font-size: 20px;
        color: #555;
      }

      .choice-container {
        display: flex;
        justify-content: center;
        gap: 100px;
        flex-wrap: wrap;
      }

      .choice-btn {
        background: none;
        border: none;
        cursor: pointer;
        transition: 0.2s;
      }

      .choice-btn:hover {
        transform: scale(1.05);
      }

      .choice-circle {
        width: 250px;
        height: 250px;
        border: 3px solid #5a3c1c;
        border-radius: 50%;
        background: #805528;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
      }

      .choice-icon {
        font-size: 100px;
      }

      .choice-label {
        margin-top: 20px;
        font-size: 24px;
        font-weight: bold;
        text-align: center;
        color: #5e3b10;
      }

      .back-btn {
        background: none;
        border: none;
        outline: none;
        padding: 0;
        cursor: pointer;
      }
    </style>
  </head>

  <body>

    <div class="header">
      <a href="login.php">
        <img src="images/back.png" class="icon-img" alt="Back">
         </a>    

      <div class="logo">⚪ THE SHARE CARE</div>

      <a href="index.php">
        <img src="images/home.png" class="icon-img" alt="Home" />
      </a>
    </div>

    <div class="container">
      <div class="title">
        <h1>STUDENT</h1>
        <p>Are you here to:</p>
      </div>

      <div class="choice-container">
        <button class="choice-btn" onclick="location.href = 'request.php'">
          <div class="choice-circle">
            <div class="choice-icon">📋</div>
          </div>
          <div class="choice-label">Request</div>
        </button>

        <button class="choice-btn" onclick="location.href = 'donor.php'">
            <div class="choice-circle">
                <div class="choice-icon">🎁</div>
            </div>
            <div class="choice-label">Donate</div>
        </button>
      </div>
    </div>

    <?php include "footer.php"; ?>
  </body>
</html>
