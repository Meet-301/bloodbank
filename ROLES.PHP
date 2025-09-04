<?php
session_start();
?>
<!doctype html>
<html lang="en" class="no-js">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">

    <title>Select Role | Life Savior</title>

    <!-- Favicon -->
    <link rel="icon" href="admin/img/icon.jpg" type="image/jpeg">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #c20a0a;
            --primary-dark: #9e0c0c;
            --secondary: #0d6efd;
            --dark: #121212;
            --light: #f8f9fa;
            --gray: #6c757d;
            --danger: #dc3545;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #2a0c0c 0%, #0c0c2a 100%);
            color: var(--light);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Background animation */
        .blood-drops {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }

        .blood-drop {
            position: absolute;
            display: block;
            width: 20px;
            height: 20px;
            background: var(--primary);
            border-radius: 50% 50% 50% 0;
            transform: rotate(-45deg);
            animation: animate 7s linear infinite;
            bottom: -150px;
        }
        .blood-drop:nth-child(1){ left: 25%; animation-delay:0s;}
        .blood-drop:nth-child(2){ left: 45%; animation-delay:2s; animation-duration:12s;}
        .blood-drop:nth-child(3){ left: 70%; animation-delay:4s;}
        .blood-drop:nth-child(4){ left: 10%; animation-delay:6s;}

        @keyframes animate {
            0% {transform: translateY(0) rotate(-45deg); opacity:1;}
            70% {opacity:0.8;}
            100% {transform: translateY(-1000px) rotate(-45deg); opacity:0;}
        }

        /* Container */
        .login-container {
            width: 100%;
            max-width: 600px;
            background: rgba(30, 30, 40, 0.85);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .login-header {
            padding: 30px 20px;
            background: rgba(198, 10, 10, 0.2);
            border-bottom: 2px solid var(--primary);
        }
        .login-header h1 {
            font-size: 28px; font-weight:600; margin-bottom:10px;
        }
        .login-header p {
            font-size: 16px; color: rgba(255,255,255,0.7);
        }

        /* Role Cards */
        .roles {
            display: flex;
            justify-content: space-around;
            gap: 20px;
            padding: 40px 20px;
            flex-wrap: wrap;
        }

        .role-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 12px;
            padding: 30px 20px;
            width: 220px;
            transition: all 0.3s ease;
            cursor: pointer;
            text-align: center;
        }

        .role-card i {
            font-size: 50px;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .role-card h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .role-card p {
            font-size: 14px;
            color: rgba(255,255,255,0.7);
        }

        .role-card:hover {
            background: rgba(198, 10, 10, 0.15);
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(198, 10, 10, 0.4);
        }

        .footer-links {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .footer-links a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 14px;
        }
        .footer-links a:hover {
            color: var(--primary);
        }

    </style>
</head>

<body>
    <div class="blood-drops">
        <div class="blood-drop"></div>
        <div class="blood-drop"></div>
        <div class="blood-drop"></div>
        <div class="blood-drop"></div>
    </div>

    <div class="login-container">
        <div class="login-header">
            <h1>LifeSavior</h1>
            <p>Please select your role to continue</p>
        </div>

        <div class="roles">
            <a href="index.php" class="role-card">
                <i class="fas fa-hand-holding-heart"></i>
                <h3>Donor</h3>
                <p>Register and donate blood to save lives.</p>
            </a>

            <a href="IR-before.php" class="role-card">
                <i class="fas fa-procedures"></i>
                <h3>Recipient</h3>
                <p>Request blood in emergency and manage needs.</p>
            </a>
        </div>

        <div class="footer-links">
            <a href="index.php"><i class="fas fa-home"></i> Back to Home</a>
        </div>
    </div>

</body>
</html>
