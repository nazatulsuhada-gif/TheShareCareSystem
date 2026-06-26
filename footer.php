<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        footer {
            background-color: #5e3b10;
            padding: 30px 20px 10px;
            color: #d6d5d5;
            margin-top: 50px;
            border-radius: 12px;
        }

        .footer-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }

        .footer-section h3 {
            margin-bottom: 8px;
            font-size: 20px;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
        }

        .footer-section a {
            text-decoration: none;
            color: #d6d5d5;
        }

        .contact-info {
            list-style: disc;
            padding-left: 20px;
        }

        .footer-bottom {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #ffffff;
            text-align: center;
            font-size: 13px;
        }

        body.dark-mode footer {
            background-color: #2a2a2a;
            color: #f5f5f5;
        }

        body.dark-mode .footer-section a {
            color: #f5f5f5;
        }

        body.dark-mode .footer-bottom {
            border-top: 1px solid #555;
        }

        .contact-section{
    text-align:right;
}

.contact-section p{
    margin:0;
    font-size:16px;
    line-height:1.4;
}

.contact-section h3{
    margin:14px 0 6px;
}

.admin-link{
    font-size:10px;
    opacity:.65;
    color:#d6d5d5;
    text-decoration:none;
}

.admin-link:hover{
    opacity:1;
}
    </style>

    <base target="_parent">

</head>

<body id="footerBody">
   <footer>

    <div class="footer-container">

        <div class="footer-section">
            <h3>The Share Care</h3>
            <p>Spreading kindness<br>through community action.</p>
        </div>

        <div class="footer-section">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="faq.php">FAQs</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
        </div>

      <div class="footer-section contact-section">

    <p>info@sharecare.com</p>
    <p>03 2276 2116</p>

   

</div>
    </div>

    <div class="footer-bottom">
        © 2026 The Share Care. All rights reserved.
    </div>

</footer>
</body>

</html>