<?php session_start();
error_reporting(0);
include('includes/config.php');
if(isset($_POST['login'])) {
    $email=$_POST['email'];
    $password=md5($_POST['password']);
    $sql ="SELECT id, Status FROM tblrecipients WHERE EmailId=:email and Password=:password";
    $query=$dbh->prepare($sql);
    $query->bindParam(':email',$email,PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);
     if($query->rowCount() > 0) {
        foreach ($results as $result) {
            $_SESSION['bbdmsdid']=$result->id;
        }
        $_SESSION['login']=$_POST['email'];
        echo "<script type='text/javascript'> document.location ='index-recipient.php'; </script>";
    } else {
        echo "<script>alert('Invalid Details');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipient Login | Life Savior</title>
    <meta name="description" content="Login to your recipient account">
    
    <!-- Favicon -->
    <link rel="icon" href="images/icon.jpg" type="image/jpeg">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary: #e63946;
            --secondary: #1d3557;
            --light: #f1faee;
            --accent: #a8dadc;
            --dark: #457b9d;
        }

        ul {
            padding-left: 0;
            list-style: none;
        }

        li {
            list-style: none;
        }

        a {
            text-decoration: none;
        }
        
        body {
            font-family: 'Open Sans', sans-serif;
            color: #333;
            line-height: 1.6;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
        }
        
        .hero-banner {
            background: linear-gradient(rgba(29, 53, 87, 0.8), rgba(29, 53, 87, 0.8)), url('images/hero-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        
        .breadcrumb-container {
            background-color: var(--light);
            padding: 15px 0;
        }
        
        .breadcrumb-item.active {
            color: var(--primary);
            font-weight: 600;
        }
        
        .login-section {
            padding: 80px 0;
        }
        
        .login-card {
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            max-width: 500px;
            margin: 0 auto;
        }
        
        .section-title {
            position: relative;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .section-title:after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: var(--primary);
            margin: 15px auto;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--secondary);
            margin-bottom: 8px;
        }
        
        .form-control {
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(230, 57, 70, 0.25);
        }
        
        .btn-login {
            background-color: var(--primary);
            border-color: var(--primary);
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s;
            width: 100%;
        }
        
        .btn-login:hover {
            background-color: #c1121f;
            border-color: #c1121f;
            transform: translateY(-2px);
        }
        
        .login-links {
            text-align: center;
            margin-top: 20px;
        }
        
        .login-links a {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .login-links a:hover {
            color: var(--secondary);
            text-decoration: underline;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
		.footer {
            background-color: var(--secondary);
            color: white;
            padding: 60px 0 20px;
        }
        
        .footer-links h5 {
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .footer-links ul {
            list-style: none;
            padding-left: 0;
        }
        
        .footer-links li {
            margin-bottom: 10px;
        }
        
        .footer-links a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-links a:hover {
            color: white;
        }
        
        .social-icons a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background-color: rgba(255,255,255,0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            margin-right: 10px;
            color: white;
            transition: all 0.3s;
        }
        
        .social-icons a:hover {
            background-color: var(--primary);
            transform: translateY(-3px);
        }
        
        .copyright {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 20px;
            margin-top: 40px;
            text-align: center;
            opacity: 0.7;
        }
        
        .emergency-banner {
            background-color: var(--primary);
            color: white;
            padding: 15px 0;
            text-align: center;
            font-weight: 600;
            margin-bottom: 0;
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
        }
    </style>
</head>

<body>
   
    
    <!-- Hero Banner -->
    <section class="hero-banner">
        <div class="container">
            <h1>Recipient Login</h1>
            <p class="lead">Access your recipient account to find blood donors</p>
        </div>
    </section>
    
    <!-- Breadcrumb -->
    <div class="breadcrumb-container">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index-recipient.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Login</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <!-- Login Section -->
    <section class="login-section">
        <div class="container">
            <div class="login-card">
                <h2 class="section-title">Login to Your Account</h2>
                
                <?php if(isset($_POST['login']) && $query->rowCount() == 0) { ?>
                    <div class="alert alert-danger">
                        Invalid email or password. Please try again.
                    </div>
                <?php } ?>
                
                <form action="#" method="post" name="login">
                    <div class="form-group">
                        <label for="email" class="form-label">Email ID</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <div class="form-group mt-4">
                        <button type="submit" name="login" class="btn btn-login text-white">
                            <i class="fas fa-sign-in-alt me-2"></i> Login
                        </button>
                    </div>
                    <div class="login-links">
                        <p class="mb-2">
                            Don't have an account? 
                            <a href="sign-up-recipient.php">Create one now</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </section>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery-3.6.0.min.js"></script>
</body>
</html>