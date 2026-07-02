<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us - The Share Care</title>

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
    border: 1px solid #999;
    background: #805528;
    padding:15px 20px;
    border-radius: 12px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
}

.logo{
    font-weight:bold;
    font-size:20px;
    color:white;
}

nav ul{
    list-style:none;
    display:flex;
    gap:30px;
}

nav a{
    text-decoration:none;
    color:white;
    font-weight:bold;
}

nav a:hover{
    text-decoration:underline;
}

.hero{
    max-width:1200px;
    width:90%;
    height:520px;
    margin:50px auto;
    position:relative;
    overflow:hidden;
    border-radius:20px;
}

.hero img{
    width:100%;
    height:100%;
    object-fit:cover;
    filter:brightness(60%);
}

.hero-text{
    position:absolute;
    top:50%;
    left:50%;
    transform:translate(-50%, -50%);
    text-align:center;
    color:white;
}

.hero-text h1{
    font-size:64px;
}

.hero-text p{
    font-size:22px;
    font-weight:bold;
}

.who-we-are{
    max-width:1200px;
    width:90%;
    margin:50px auto;
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:40px;
    align-items:center;
}

.who-image img{
    width:100%;
    height:450px;
    object-fit:cover;
    border-radius:20px;
}

.who-content h2{
    font-size:42px;
    color:#5e3b10;
}

.line{
    width:70px;
    height:3px;
    background:#805528;
    margin:10px 0 25px;
}

.who-content p{
    font-size:18px;
    line-height:1.8;
    color:#333;
}

.features{
    display:flex;
    gap:20px;
    margin-top:30px;
}

.feature{
    text-align:center;
    flex:1;
}

.icon{
    margin:0 auto 10px;
}

.icon img{
    width:90px;
    height:auto;
    display:block;
    margin:auto;
    transition:0.3s;
}

.icon img:hover{
    transform:scale(1.1);
}

.cards{
    max-width:1200px;
    width:90%;
    margin:50px auto;
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:30px;
}

.card{
    background:#f8f1d8;
    border-radius:20px;
    padding:35px;
    min-height:400px;
    border:1px solid #eadfb8;
}

.card h2{
    font-size:36px;
    color:#5e3b10;
}

.card-line{
    width:60px;
    height:3px;
    background:#805528;
    margin:10px auto 20px;  
}

.vision-card{
    text-align:center;
}

.vision-card p{
    font-size:18px;
    line-height:1.7;
}

.vision-card img{
    display:block;
    margin:35px auto 0;
    width:520px;
    max-width:100%;
    object-fit:contain;
}

.mission-card{
    text-align:center;
}

.mission-card p{
    font-size:18px;
    line-height:1.8;
    margin:10px 0;
    color:#333;
}

.mission-card img{
    display:block;
    margin:25px auto 0;
    width:260px;
    max-width:100%;
    object-fit:contain;
}

@media(max-width:768px){

    .hero{
        height:320px;
    }

    .hero-text h1{
        font-size:38px;
    }

    .who-we-are,
    .cards{
        grid-template-columns:1fr;
    }

    .vision-card img{
        width:320px;
    }

    .mission-card img{
        width:220px;
    }

    .card h2{
        font-size:30px;
    }

    .mission-card p{
        font-size:16px;
    }

    .icon img{
        width:70px;
    }
}

@media (max-width: 992px){

    nav ul{
        gap:20px;
    }

    .hero{
        height:420px;
    }

    .hero-text h1{
        font-size:52px;
    }

    .hero-text p{
        font-size:18px;
    }

    .who-content h2{
        font-size:36px;
    }

    .cards{
        gap:20px;
    }

    .vision-card img{
        width:400px;
    }
}

@media (max-width: 768px){

    nav{
        flex-direction:column;
        gap:15px;
        text-align:center;
    }

    nav ul{
        flex-wrap:wrap;
        justify-content:center;
        gap:15px;
    }

    .hero{
        height:320px;
    }

    .hero-text{
        width:90%;
    }

    .hero-text h1{
        font-size:38px;
    }

    .hero-text p{
        font-size:16px;
    }

    .who-we-are,
    .cards{
        grid-template-columns:1fr;
    }

    .who-content{
        text-align:center;
    }

    .line{
        margin:10px auto 25px;
    }

    .features{
        flex-direction:column;
        gap:25px;
    }

    .vision-card img{
        width:320px;
    }

    .mission-card img{
        width:220px;
    }

    .card h2{
        font-size:30px;
    }

    .mission-card p,
    .vision-card p,
    .who-content p{
        font-size:16px;
    }

    .icon img{
        width:70px;
    }

    
}

@media (max-width: 480px){

    body{
        padding:10px;
    }

    .logo{
        font-size:18px;
    }

    nav ul li a{
        font-size:14px;
    }

    .hero{
        height:250px;
    }

    .hero-text h1{
        font-size:30px;
    }

    .hero-text p{
        font-size:14px;
    }

    .who-content h2,
    .card h2{
        font-size:26px;
    }

    .card{
        padding:25px;
    }

    .vision-card img{
        width:250px;
    }

    .mission-card img{
        width:180px;
    }

    
}

</style>
</head>

<body>

<nav>
<div class="logo">⚪ THE SHARE CARE</div>

<ul>
<li><a href="index.php">Home</a></li>
<li><a href="about.php"><u>About Us</u></a></li>
<li><a href="faq.php">FAQs</a></li>
<li><a href="contact.php">Contact Us</a></li>
</ul>
</nav>

<section class="hero">
<img src="images/about-banner.png" alt="About Banner">
<div class="hero-text">
<h1>About Us</h1>
<p>Sharing today, caring always.</p>
<p>Building a better community together.</p>
</div>
</section>

<section class="who-we-are">

<div class="who-image">
<img src="images/about-donation.png" alt="Donation Box">
</div>

<div class="who-content">
<h2>Who We Are</h2>
<div class="line"></div>

<p>The Share Care is a community-driven platform that connects people who want to donate with those who need support.</p>

<p>Our goal is to encourage kindness, reduce waste, and create a caring environment where everyone can contribute to helping others.</p>

<div class="features">

<div class="feature">
<div class="icon">
<img src="images/about-community.png" alt="Community Icon">
</div>
<p>Community Driven</p>
</div>

<div class="feature">
<div class="icon">
<img src="images/about-heart.png" alt="Heart Icon">
</div>
<p>Kindness Matters</p>
</div>

<div class="feature">
<div class="icon">
<img src="images/about-leaves.png" alt="Leaves Icon">
</div>
<p>Reduce Waste</p>
</div>

</div>

</div>
</section>

<section class="cards">

<div class="card vision-card">
<h2>Our Vision</h2>
<div class="card-line"></div>

<p>To create a supportive community through the power of sharing.</p>

<img src="images/about-vision.png" alt="Vision Image">
</div>

<div class="card mission-card">
<h2>Our Mission</h2>
<div class="card-line"></div>

<p>Connect people who want to help with those in need.</p>
<p>Encourage generosity and community participation.</p>
<p>Make sharing simple, reliable, and meaningful.</p>

<img src="images/about-mission.png" alt="Mission Image">
</div>

</section>

<?php include "footer.php"; ?>

</body>
</html>
