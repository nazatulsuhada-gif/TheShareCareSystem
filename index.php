<!DOCTYPE html>
<html>
<head>
    <title>The Share Care</title>
    <meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: Arial, Helvetica, sans-serif;
}

body{
    background:#fcfae4;
    padding:20px;
    overflow-x:hidden;
}

nav{
    border:1px solid #999;
    background:#8a5a22;
    padding:15px 20px;
    border-radius:12px;

    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
}

.logo{
    color:white;
    font-size:20px;
    font-weight:bold;
}

nav ul{
    list-style:none;
    display:flex;
    gap:30px;
}

nav ul li a{
    text-decoration:none;
    color:white;
    font-weight:bold;
}

nav ul li a:hover{
    text-decoration:underline;
}

.container{
    max-width:1200px;
    width:90%;
    margin:80px auto;

    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:40px;
}

.image{
    width:55%;
}

.image img{
    width:100%;
    border-radius:30px;
    box-shadow:0px 10px 25px rgba(0,0,0,0.2);
}

.content{
    width:35%;
}

.content h1{
    font-size:85px;
    line-height:0.95;
    color:#8a5a22;
}

.line{
    width:90px;
    height:6px;
    background:#8a5a22;
    border-radius:10px;
    margin-top:20px;
    margin-bottom:25px;
}

.content p{
    color:#555;
    font-size:22px;
    line-height:1.5;
    margin-bottom:40px;
}

.btn{
    background:#a5641f;
    color:white;
    text-decoration:none;
    padding:18px 40px;
    border-radius:40px;
    font-size:20px;
    font-weight:bold;
}


@media (max-width: 992px){

    .container{
        width:95%;
        gap:30px;
    }

    .content h1{
        font-size:65px;
    }

    .content p{
        font-size:20px;
    }
}

@media (max-width: 768px){

    nav{
        flex-direction:column;
        text-align:center;
    }

    nav ul{
        flex-direction:column;
        gap:10px;
        margin-top:15px;
    }

    .container{
        flex-direction:column;
        text-align:center;
        margin:40px auto;
    }

    .image,
    .content{
        width:100%;
    }

    .content h1{
        font-size:55px;
    }

    .content p{
        font-size:18px;
        margin-bottom:30px;
    }

    .line{
        margin:15px auto 20px;
    }

    .btn{
        display:inline-block;
        padding:15px 30px;
        font-size:18px;
    }
}

@media (max-width: 480px){

    body{
        padding:10px;
    }

    .logo{
        font-size:16px;
    }

    .content h1{
        font-size:42px;
    }

    .content p{
        font-size:16px;
    }

    .btn{
        width:100%;
        max-width:250px;
    }

    .image img{
        border-radius:20px;
    }
}

</style>
</head>

<body>

<nav>

    <div class="logo">
        ⚪ THE SHARE CARE
    </div>

    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About Us</a></li>
        <li><a href="faq.php">FAQs</a></li>
        <li><a href="contact.php">Contact Us</a></li>
    </ul>

</nav>

<section class="container">

    <div class="image">
        <img src="images/donation.jpeg">
    </div>

    <div class="content">

        <h1>
            THE<br>
            SHARE<br>
            CARE
        </h1>

        <div class="line"></div>

        <p>
            Spreading kindness through community action.
        </p>

        <a href="role.php" class="btn">
            JOIN US
        </a>

    </div>

</section>


<?php include "footer.php"; ?>

</body>
</html>