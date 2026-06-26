<!doctype html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FAQ</title>

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
        margin: 40px 0 30px;
        text-align: center;
        font-size: 42px;
        color: #5e3b10;
      }

      .faq-container {
        width: 90%;
        max-width: 1000px;
        margin: 0 auto;
      }

      .faq-box {
        background: white;
        border: 1px solid #c9c9c9;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 15px;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      }

      .question {
        padding: 18px 20px;
        font-weight: bold;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #5e3b10;
        background: #faf6d1;
        font-size: 17px;
      }

      .symbol {
        font-size: 24px;
        color: #805528;
      }

      .answer {
        padding: 15px;
        border-top: 1px solid #ddd;
        display: none;
        background: #faf6d1;
      }

      footer {
        background-color: #e0e0e0;
        padding: 30px 50px 10px;
        color: #333;
        margin-top: 50px;
      }

      .footer-container {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 20px;
      }

      .footer-section h3 {
        margin: 0 0 8px;
        font-size: 24px;
      }

      .footer-section ul {
        list-style: none;
        padding: 0;
        margin: 0;
      }

      .footer-section a {
        text-decoration: none;
        color: #333;
      }

      .contact-info {
        list-style: disc;
        padding-left: 20px;
      }

      .footer-bottom {
        margin-top: 20px;
        padding-top: 10px;
        border-top: 1px solid #999;
        text-align: center;
        font-size: 14px;
      }

      @media (max-width: 992px) {
        nav ul {
          gap: 20px;
        }

        h1 {
          font-size: 36px;
        }

        .faq-container {
          width: 95%;
        }

        .question {
          font-size: 16px;
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

        .faq-container {
          width: 100%;
        }

        .question {
          padding: 15px;
          font-size: 15px;
        }

        .answer {
          font-size: 14px;
          line-height: 1.6;
        }

        .symbol {
          font-size: 20px;
        }

        iframe {
          height: 380px;
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

        .question {
          font-size: 14px;
          padding: 14px;
        }

        .answer {
          font-size: 13px;
          padding: 12px;
        }

        iframe {
          height: 450px;
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

    <h1>Frequently Asked Questions</h1>

    <div class="faq-container">
      <div class="faq-box" onclick="toggleFAQ(this)">
        <div class="question">
          What is The Share Care?
          <span class="symbol">+</span>
        </div>
        <div class="answer">
          The Share Care is a platform that connects members of Universiti Teknikal Malaysia Melaka (UTeM)
          community who wish to donate items with those who need them, helping the community support one
          another.
        </div>
      </div>

      <div class="faq-box" onclick="toggleFAQ(this)">
        <div class="question">
          Who can use The Share Care?
          <span class="symbol">+</span>
        </div>
        <div class="answer">
          The Share Care is open to all UTeM community members, including students, alumni & staff.
        </div>
      </div>

      <div class="faq-box" onclick="toggleFAQ(this)">
        <div class="question">
          Is there any fee for using the Share Care system?
          <span class="symbol">+</span>
        </div>
        <div class="answer">
          <b>No.</b> The Share Care system is free to use for both donors and recipients.
        </div>
      </div>

    <div class="faq-box" onclick="toggleFAQ(this)">
        <div class="question">
          How can I donate an item?
          <span class="symbol">+</span>
        </div>
        <div class="answer">
          Click the <b>Join Us</b> button on our homepage, create an account and log in.
          Then, go to the Upload Product page and fill in your item details.
          Once submitted please wait for the admin to review and approve your donation item.
        </div>
      </div>
      
      <div class="faq-box" onclick="toggleFAQ(this)">
        <div class="question">
          How do I request an item?
          <span class="symbol">+</span>
        </div>
        <div class="answer">
          Request item are available to UTeM <b>students</b> only.<br>
          To request an item, browse the avilable items, select the item you need and click the <b>Request</b>
          button. Once your request is submitted, the donor will be notified and your request will be viewed.
        </div>
      </div>

      <div class="faq-box" onclick="toggleFAQ(this)">
        <div class="question">
          What items can be donated?
          <span class="symbol">+</span>
        </div>
        <div class="answer">
          Users may donate items that are in good conditions and suitable for reuse, such as clothing, books, stationary and electrical or electronic items.
        </div>
      </div>
    </div>

    <script>
      function toggleFAQ(box) {
        var answer = box.querySelector(".answer");
        var symbol = box.querySelector(".symbol");

        if (answer.style.display === "block") {
          answer.style.display = "none";
          symbol.innerHTML = "+";
        } else {
          answer.style.display = "block";
          symbol.innerHTML = "−";
        }
      }
    </script>

    <iframe
      src="footer.php"
      width="100%"
      height="300"
      style="border: none"
      scrolling="no"
    >
    </iframe>
  </body>
</html>