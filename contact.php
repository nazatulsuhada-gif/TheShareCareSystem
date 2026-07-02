<?php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $phone = mysqli_real_escape_string($conn, $_POST['phone']);
  $message = mysqli_real_escape_string($conn, $_POST['message']);

  $sql = "INSERT INTO contact_messages (name, email, phone, message) VALUES ('$name', '$email', '$phone', '$message')";

  if (mysqli_query($conn, $sql)) {
    echo "<script>
            alert('Message sent successfully.');
            window.location.href = 'contact.php';
          </script>";
    exit();
  } else {
    echo "Error: " . mysqli_error($conn);
  }
}
?>

<!doctype html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Us</title>

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

    nav {
      border: 1px solid #999;
      background: #805528;
      padding: 15px 20px;
      border-radius: 12px;

      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }

    .logo {
      font-weight: bold;
      font-size: 20px;
      color: white;
    }

    nav ul {
      list-style: none;
      display: flex;
      gap: 30px;
    }

    nav a {
      text-decoration: none;
      color: white;
      font-weight: bold;
    }

    nav a:hover {
      text-decoration: underline;
    }

    nav a:hover {
      text-decoration: underline;
    }

    h1 {
      text-align: center;
      margin: 40px 0 30px;
      font-size: 42px;
      color: #5e3b10;
    }

    .form-box {
      width: 90%;
      max-width: 600px;
      margin: 0 auto;

      background: #faf6d1;
      padding: 30px;
      border: 1px solid #5e3b10;
      border-radius: 12px;

      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .row {
      margin-bottom: 15px;
    }

    label {
      display: block;
      font-weight: bold;
      margin-bottom: 8px;
      color: #5e3b10;
    }

    input,
    textarea {
      width: 100%;
      padding: 12px;
      border: 1px solid #bbb;
      border-radius: 10px;
      font-size: 15px;
    }

    input:focus,
    textarea:focus {
      outline: none;
      border-color: #805528;
    }

    textarea {
      height: 120px;
      resize: vertical;
    }

    .btn {
      background: #805528;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 25px;
      cursor: pointer;
      width: 100%;
      font-size: 16px;
      font-weight: bold;
    }

    .btn:hover {
      background: #5e3b10;
    }

    @media (max-width: 992px) {

      nav ul {
        gap: 20px;
      }

      h1 {
        font-size: 36px;
      }

      .form-box {
        width: 95%;
      }
    }

    @media (max-width: 768px) {

      nav {
        flex-direction: column;
        gap: 15px;
        text-align: center;
      }

      nav ul {
        flex-wrap: wrap;
        justify-content: center;
        gap: 15px;
        margin-top: 0;
      }

      h1 {
        font-size: 32px;
        margin: 30px 0 25px;
      }

      .form-box {
        width: 100%;
        padding: 20px;
      }

      label {
        font-size: 15px;
      }

      input,
      textarea {
        font-size: 14px;
      }

      .btn {
        font-size: 15px;
        padding: 14px;
      }


    }

    @media (max-width: 480px) {

      body {
        padding: 10px;
      }

      .logo {
        font-size: 18px;
      }

      nav ul li a {
        font-size: 14px;
      }

      h1 {
        font-size: 26px;
      }

      .form-box {
        padding: 15px;
      }

      input,
      textarea {
        padding: 10px;
      }


    }
  </style>
</head>

<body>
  <nav>
    <div class="logo">⚪ THE SHARE CARE</div>

    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="about.php">About Us</a></li>
      <li><a href="faq.php">FAQs</a></li>
      <li><a href="contact.php">Contact Us</a></li>
    </ul>
  </nav>

  <h1>Contact Us</h1>

  <div class="form-box">
    <form method="POST">
      <div class="row">

        <label>Name</label>
        <input
          type="text"
          name="name"
          placeholder="Enter your full name"
          required />
      </div>

      <div class="row">
        <label>Email</label><input
          type="email"
          name="email"
          placeholder="Enter your email address"
          required />
      </div>

      <div class="row">
        <label>Phone Number</label>
        <input
          type="text"
          name="phone"
          placeholder="Enter your phone number"
          required />
      </div>

      <div class="row">
        <label>Your Message</label>
      <textarea
        name="message"
        placeholder="Write your message here..."
        required></textarea>
      </div>

      <button class="btn" type="submit">SEND</button>
    </form>
  </div>

    <?php include "footer.php"; ?>
</body>

</html>
