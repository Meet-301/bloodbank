<?php 
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['bbdmsdid']==0)) {
  header('location:logout.php');
} else {

if(isset($_POST['change']))
{
$uid=$_SESSION['bbdmsdid'];
$cpassword=md5($_POST['currentpassword']);
$newpassword=md5($_POST['newpassword']);
$sql ="SELECT ID FROM tblblooddonars WHERE id=:uid and Password=:cpassword";
$query= $dbh -> prepare($sql);
$query-> bindParam(':uid', $uid, PDO::PARAM_STR);
$query-> bindParam(':cpassword', $cpassword, PDO::PARAM_STR);
$query-> execute();
$results = $query -> fetchAll(PDO::FETCH_OBJ);

if($query -> rowCount() > 0)
{
$con="update tblblooddonars set Password=:newpassword where id=:uid";
$chngpwd1 = $dbh->prepare($con);
$chngpwd1-> bindParam(':uid', $uid, PDO::PARAM_STR);
$chngpwd1-> bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
$chngpwd1->execute();

echo '<script>alert("Your password successully changed")</script>';
} else {
echo '<script>alert("Your current password is wrong")</script>';

}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Change Password | Life Savior</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon -->
    <link rel="icon" href="images/icon.jpg" type="image/jpeg">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="css/fontawesome-all.css">
    
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
            font-family: 'Roboto Condensed', sans-serif;
        }
        
        .page-header {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('images/hero-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
            margin-bottom: 40px;
            text-align: center;
        }
        
        .page-title {
            position: relative;
            margin-bottom: 30px;
            font-weight: 700;
        }
        
        .page-title:after {
            content: "";
            display: block;
            width: 80px;
            height: 3px;
            background: var(--primary);
            margin: 15px auto 0;
        }
        
        .password-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border: none;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .card-header {
            background-color: var(--primary);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
            font-weight: 600;
        }
        
        .form-label {
            font-weight: 500;
            color: var(--dark);
        }
        
        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
        
        .btn-password {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 12px 25px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .btn-password:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }
        
        .password-strength {
            height: 5px;
            background: #e9ecef;
            margin-top: 5px;
            border-radius: 3px;
            overflow: hidden;
        }
        
        .strength-meter {
            height: 100%;
            width: 0;
            transition: width 0.3s;
        }
        
        .breadcrumb {
            background-color: transparent;
            padding: 0;
            font-size: 14px;
        }
        
        .breadcrumb-item.active {
            color: var(--primary);
            font-weight: 500;
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
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: white;
            text-decoration: none;
        }

    </style>
</head>

<body>
    <!-- Header -->
    <?php include('includes/header.php'); ?>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title">Change Password</h1>
            <p class="lead">Update your account password securely</p>
        </div>
    </div>

    <!-- Page Content -->
    <div class="container py-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Change Password</li>
            </ol>
        </nav>
        
        <!-- Password Change Card -->
        <div class="card password-card">
            <div class="card-header">
                <i class="fas fa-key me-2"></i> Password Settings
            </div>
            <div class="card-body">
                <form action="#" method="post" onsubmit="return checkpass();" name="changepassword">
                    <div class="mb-4">
                        <label for="currentpassword" class="form-label">Current Password</label>
                        <input type="password" class="form-control" name="currentpassword" id="currentpassword" required>
                        <small class="text-muted">Enter your current password</small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="newpassword" class="form-label">New Password</label>
                        <input type="password" name="newpassword" class="form-control" id="newpassword" required>
                        <div class="password-strength">
                            <div class="strength-meter" id="strengthMeter"></div>
                        </div>
                        <small class="text-muted">Minimum 8 characters with at least 1 number</small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="confirmpassword" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="confirmpassword" id="confirmpassword" required>
                        <small class="text-muted">Re-enter your new password</small>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" name="change" class="btn btn-password text-white">
                            <i class="fas fa-save me-2"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5>Life Savior</h5>
                    <p class="mt-3">Our mission is to connect blood donors with recipients quickly and efficiently,
                        saving lives one donation at a time.</p>
                </div>
                <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                    <h5>Quick Links</h5>
                    <ul class="mt-3">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="donor-list.php">Find Donors</a></li>
                        <li><a href="about.php">About</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                    <h5>Our Location</h5>
                    <div class="map-responsive mb-4">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3559.9940459226576!2d75.81897407524825!3d26.84213516207256!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x396db51861c1ad9d%3A0xb387a4d4505bb4b!2sSMS%20Hospital%2C%20Jaipur!5e0!3m2!1sen!2sin!4v1628311460300!5m2!1sen!2sin"
                            width="100%" height="250" style="border:0; border-radius: 10px;" allowfullscreen=""
                            loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <style>
                        .map-responsive {
                            position: relative;
                            padding-bottom: 56.25%;
                            height: 0;
                            overflow: hidden;
                            border-radius: 10px;
                        }

                        .map-responsive iframe {
                            position: absolute;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            border: 0;
                        }
                    </style>

                </div>
                <div class="col-lg-4">
                    <h5>Contact Info</h5>
                    <ul class="mt-3">
                        <li><i class="fas fa-map-marker-alt me-2"></i> 123 Health St, Medical City</li>
                        <li><i class="fas fa-phone me-2"></i> +1 234 567 8900</li>
                        <li><i class="fas fa-envelope me-2"></i> info@bloodbank.com</li>
                        <li><i class="fas fa-clock me-2"></i> 24/7 Emergency Service</li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <p class="mb-0">&copy; <?php echo date("Y"); ?> Blood Bank Donor Management System. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="js/jquery-2.2.3.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script>
        // Password validation
        function checkpass() {
            if(document.changepassword.newpassword.value != document.changepassword.confirmpassword.value) {
                alert('New Password and Confirm Password field does not match');
                document.changepassword.confirmpassword.focus();
                return false;
            }
            
            if(document.changepassword.newpassword.value.length < 8) {
                alert('Password must be at least 8 characters long');
                return false;
            }
            
            return true;
        }
        
        // Password strength indicator
        document.getElementById('newpassword').addEventListener('input', function() {
            const password = this.value;
            const strengthMeter = document.getElementById('strengthMeter');
            let strength = 0;
            
            if (password.length >= 8) strength += 1;
            if (password.match(/[a-z]/)) strength += 1;
            if (password.match(/[A-Z]/)) strength += 1;
            if (password.match(/[0-9]/)) strength += 1;
            if (password.match(/[^a-zA-Z0-9]/)) strength += 1;
            
            const width = (strength / 5) * 100;
            strengthMeter.style.width = width + '%';
            
            if (strength <= 2) {
                strengthMeter.style.backgroundColor = '#dc3545';
            } else if (strength <= 4) {
                strengthMeter.style.backgroundColor = '#ffc107';
            } else {
                strengthMeter.style.backgroundColor = '#28a745';
            }
        });
    </script>
</body>

</html>
<?php } ?>