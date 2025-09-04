<?php
session_start();
include('includes/config.php');
if(isset($_POST['submit']))
{
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $newpassword = ($_POST['newpassword']);
    $sql = "SELECT Email FROM tbladmin WHERE Email=:email and MobileNumber=:mobile";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    if($query->rowCount() > 0)
    {
        $con = "update tbladmin set Password=:newpassword where Email=:email and MobileNumber=:mobile";
        $chngpwd1 = $dbh->prepare($con);
        $chngpwd1->bindParam(':email', $email, PDO::PARAM_STR);
        $chngpwd1->bindParam(':mobile', $mobile, PDO::PARAM_STR);
        $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
        $chngpwd1->execute();
        echo "<script>alert('Your Password successfully changed');</script>";
    }
    else {
        echo "<script>alert('Email id or Mobile no is invalid');</script>"; 
    }
}
?>
<!doctype html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Life Savior | Forgot Password</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --danger: #e53e3e;
            --light: #f8f9fa;
            --dark: #212529;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f5f7fb 0%, #e3edf7 100%);
            overflow: hidden;
            position: relative;
        }
        
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('img/banner.png') no-repeat center center;
            background-size: cover;
            opacity: 0.1;
            z-index: -1;
        }
        
        .auth-container {
            max-width: 450px;
            width: 100%;
            padding: 2rem;
        }
        
        .auth-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .auth-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }
        
        .auth-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            text-align: center;
            padding: 2.5rem 2rem;
            position: relative;
        }
        
        .auth-header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,224L48,218.7C96,213,192,203,288,197.3C384,192,480,192,576,202.7C672,213,768,235,864,234.7C960,235,1056,213,1152,197.3C1248,181,1344,171,1392,165.3L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') bottom center no-repeat;
            background-size: cover;
        }
        
        .auth-logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2.5rem;
        }
        
        .auth-title {
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            position: relative;
        }
        
        .auth-subtitle {
            font-weight: 400;
            opacity: 0.9;
            font-size: 1rem;
        }
        
        .auth-body {
            padding: 2rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }
        
        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
            z-index: 10;
        }
        
        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.75rem 1rem 0.75rem 3rem;
            height: auto;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }
        
        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            cursor: pointer;
            z-index: 10;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            width: 100%;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 20px rgba(67, 97, 238, 0.4);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .auth-footer {
            text-align: center;
            padding-top: 1.5rem;
            color: #718096;
        }
        
        .auth-footer a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .auth-footer a:hover {
            color: var(--secondary);
            text-decoration: underline;
        }
        
        .password-strength {
            height: 5px;
            border-radius: 3px;
            margin-top: 5px;
            background: #e2e8f0;
            overflow: hidden;
        }
        
        .password-strength-meter {
            height: 100%;
            width: 0;
            transition: width 0.3s ease;
        }
        
        .password-match {
            margin-top: 5px;
            font-size: 0.85rem;
            display: none;
        }
        
        .password-match.valid {
            color: #38a169;
        }
        
        .password-match.invalid {
            color: #e53e3e;
        }
        
        .back-home {
            display: inline-flex;
            align-items: center;
            margin-top: 1.5rem;
            color: var(--primary);
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .back-home:hover {
            color: var(--secondary);
            transform: translateX(-3px);
        }
        
        @media (max-width: 576px) {
            .auth-container {
                padding: 1rem;
            }
            
            .auth-header {
                padding: 1.5rem 1rem;
            }
            
            .auth-body {
                padding: 1.5rem;
            }
        }
    </style>
    <script type="text/javascript">
        function valid() {
            if(document.chngpwd.newpassword.value != document.chngpwd.confirmpassword.value) {
                alert("New Password and Confirm Password Field do not match !!");
                document.chngpwd.confirmpassword.focus();
                return false;
            }
            return true;
        }
    </script>
</head>

<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <i class="fas fa-tint"></i>
                </div>
                <h1 class="auth-title">Reset Your Password</h1>
                <p class="auth-subtitle">Enter your details to reset your password</p>
            </div>
            
            <div class="auth-body">
                <form method="post" name="chngpwd" onsubmit="return valid();">
                    <div class="input-group">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" class="form-control" placeholder="Email Address" required="true" name="email">
                    </div>
                    
                    <div class="input-group">
                        <i class="fas fa-mobile-alt input-icon"></i>
                        <input type="text" class="form-control" name="mobile" placeholder="Mobile Number" required="true" maxlength="10" pattern="[0-9]+">
                    </div>
                    
                    <div class="input-group">
                        <i class="fas fa-lock input-icon"></i>
                        <input class="form-control" type="password" name="newpassword" placeholder="New Password" required="true" id="newPassword"/>
                        <i class="fas fa-eye password-toggle" id="toggleNewPassword"></i>
                        <div class="password-strength">
                            <div class="password-strength-meter" id="passwordStrengthMeter"></div>
                        </div>
                    </div>
                    
                    <div class="input-group">
                        <i class="fas fa-lock input-icon"></i>
                        <input class="form-control" type="password" name="confirmpassword" placeholder="Confirm Password" required="true" id="confirmPassword"/>
                        <i class="fas fa-eye password-toggle" id="toggleConfirmPassword"></i>
                        <div class="password-match" id="passwordMatch"></div>
                    </div>
                    
                    <button class="btn btn-primary" name="submit" type="submit">
                        <i class="fas fa-sync-alt me-2"></i> Reset Password
                    </button>
                    
                    <div class="auth-footer">
                        <p class="mt-3">Remembered your password? <a href="index.php">Sign In</a></p>
                        <a href="../index.php" class="back-home">
                            <i class="fas fa-arrow-left me-2"></i> Back to Home
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Loading Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Toggle password visibility
            $('#toggleNewPassword').click(function() {
                const passwordInput = $('#newPassword');
                const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
                passwordInput.attr('type', type);
                $(this).toggleClass('fa-eye fa-eye-slash');
            });
            
            $('#toggleConfirmPassword').click(function() {
                const passwordInput = $('#confirmPassword');
                const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
                passwordInput.attr('type', type);
                $(this).toggleClass('fa-eye fa-eye-slash');
            });
            
            // Password strength indicator
            $('#newPassword').on('input', function() {
                const password = $(this).val();
                let strength = 0;
                
                if (password.length >= 8) strength += 25;
                if (/[A-Z]/.test(password)) strength += 25;
                if (/[0-9]/.test(password)) strength += 25;
                if (/[^A-Za-z0-9]/.test(password)) strength += 25;
                
                $('#passwordStrengthMeter').css('width', strength + '%');
                
                if (strength < 50) {
                    $('#passwordStrengthMeter').css('background', '#e53e3e');
                } else if (strength < 75) {
                    $('#passwordStrengthMeter').css('background', '#dd6b20');
                } else {
                    $('#passwordStrengthMeter').css('background', '#38a169');
                }
            });
            
            // Password match indicator
            $('#confirmPassword').on('input', function() {
                const password = $('#newPassword').val();
                const confirmPassword = $(this).val();
                
                if (confirmPassword.length === 0) {
                    $('#passwordMatch').hide();
                    return;
                }
                
                if (password === confirmPassword) {
                    $('#passwordMatch').html('<i class="fas fa-check-circle me-2"></i>Passwords match').removeClass('invalid').addClass('valid').show();
                } else {
                    $('#passwordMatch').html('<i class="fas fa-times-circle me-2"></i>Passwords do not match').removeClass('valid').addClass('invalid').show();
                }
            });
            
            // Form validation
            function valid() {
                if($('#newPassword').val() !== $('#confirmPassword').val()) {
                    alert("New Password and Confirm Password Field do not match !!");
                    $('#confirmPassword').focus();
                    return false;
                }
                return true;
            }
        });
    </script>
</body>
</html>