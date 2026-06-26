<style>
/* header */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 25px;
    background: #805528;
    border-radius: 15px;
    font-family: Arial, Helvetica, sans-serif;
}

.logo {
    font-size: 20px;
    font-weight: bold;
    color: #fff;
}


.header-right {
    display: flex;
    align-items: center;
    gap: 15px; 
}

.icon-img {
    width: 35px;
    height: 35px;
    object-fit: contain;
    cursor: pointer;
    filter: brightness(0) invert(1);
}

/* responsive */
@media (max-width: 768px) {
    .header {
        padding: 15px;
    }
}


@media (max-width: 480px) {
    .logo {
        font-size: 16px; 
    }

    .icon-img {
        width: 30px;
        height: 30px;
    }
    
    .header-right {
        gap: 10px; 
    }
}
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="header">
        <a href="role.php">
            <img src="images/back.png" class="icon-img" alt="Back">
        </a>

        <div class="logo">⚪ THE SHARE CARE</div>

        <div class="header-right">
            <a href="report.php?donation_id=<?php echo $id; ?>">
                <img src="images/report-btn.png" class="icon-img" alt="Report">
            </a>
            <a href="index.php">
                <img src="images/home.png" class="icon-img" alt="Home">
            </a>
        </div>
    </div>
</body>
</html>