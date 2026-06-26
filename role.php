<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>The Share Care - Role Selection</title>

        <style>

            *{
                margin:0;
                padding:0;
                box-sizing:border-box;
                font-family:Arial, Helvetica, sans-serif;
            }

            body{
                background:#fcfae4;
                padding:20px;
                overflow-x:hidden;
            }

            .header{
                display:flex;
                justify-content:space-between;
                align-items:center;
                padding:15px 20px;
                border:1px solid #999;
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
                object-fit:contain;
                cursor:pointer;

                filter: brightness(0) invert(1);
            }

            .container{
                max-width:1200px;
                width:90%;
                margin:50px auto;
            }

            .title{
                text-align:center;
                margin-bottom:50px;
            }

            .title h1{
                font-size:64px;
                margin-bottom:10px;
                color:#5e3b10;
            }

            .title p{
                font-size:22px;
                color:#5e3b10;
            }

            .roles{
                display:flex;
                justify-content:center;
                gap:40px;
                flex-wrap:wrap;
            }

            .role-btn{
                background:none;
                border:none;
                cursor:pointer;
                transition:0.2s;
            }

            .role-btn:hover{
                transform:scale(1.05);
            }

            .role-icon{
                width:280px;
                height:280px;
                border:3px solid #805528;
                border-radius:50%;
                position:relative;
                margin-bottom:20px;
                background:#faf6d1;

                box-shadow:0 2px 8px rgba(0,0,0,0.08);
            }

            .role-label{
                font-size:22px;
                font-weight:bold;
                color:#5e3b10;
            }

            .back-btn{
                background:none;
                border:none;
                outline:none;
                padding:0;
                cursor:pointer;
            }

            .alumni-img {
                background-image: url('images/alumni.jpeg');
                background-size: cover;
                background-position: center;
            }

            .staff-img {
                background-image: url('images/staff.png');
                background-size: cover;
                background-position: center;
            }

            .student-img {
                background-image: url('images/student.png');
                background-size: cover;
                background-position: center;
            }

            @media (max-width: 992px){

                .title h1{
                    font-size:52px;
                }

                .title p{
                    font-size:20px;
                }

                .role-icon{
                    width:230px;
                    height:230px;
                }
            }

            @media (max-width: 768px){

            .header{
                padding:15px;
            }

                .container{
                    width:95%;
                }

                .title{
                    margin-bottom:40px;
                }

                .title h1{
                    font-size:42px;
                }

                .title p{
                    font-size:18px;
                }

                .roles{
                    gap:30px;
                }

                .role-icon{
                    width:180px;
                    height:180px;
                }

                .role-label{
                    font-size:18px;
                }

                
            }

            @media (max-width: 480px){

                body{
                    padding:10px;
                }

                .logo{
                    font-size:18px;
                }

                .icon-img{
                    width:30px;
                    height:30px;
                }

                .title h1{
                    font-size:34px;
                }

                .title p{
                    font-size:16px;
                }

                .role-icon{
                    width:150px;
                    height:150px;
                }

                .role-label{
                    font-size:16px;
                }

            }

        </style>
    </head>

<body>

<div class="header">

    <a href="index.php">
    <img src="images/back.png" class="icon-img" alt="Back">
    </a>

    <div class="logo">⚪ THE SHARE CARE</div>

    <a href="index.php">
        <img src="images/home.png" class="icon-img" alt="Home">
    </a>

</div>

<div class="container">

    <div class="title">
        <h1>ROLE</h1>
        <p>Please select your role:</p>
    </div>

    <div class="roles">

    <button class="role-btn" onclick="selectRole('Alumni')">
        <div class="role-icon alumni-img"></div>
        <div class="role-label">Alumni</div>
    </button>

    <button class="role-btn" onclick="selectRole('Staff')">
        <div class="role-icon staff-img"></div>
        <div class="role-label">Staff</div>
    </button>

    <button class="role-btn" onclick="selectRole('Student')">
        <div class="role-icon student-img"></div>
        <div class="role-label">Student</div>
    </button>

</div>

</div>

<?php include "footer.php"; ?>

    <script>

        function selectRole(role){
            window.location.href = "login.php?role=" + role.toLowerCase();
        }

    </script>

</body>
</html>