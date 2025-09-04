<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
{	
header('location:index.php');
}
else{
// Code for change password	
if(isset($_POST['submit']))
{
$password=md5($_POST['password']);
$newpassword=md5($_POST['newpassword']);
$username=$_SESSION['alogin'];
$sql ="SELECT Password FROM tbladmin WHERE UserName=:username and Password=:password";
$query= $dbh -> prepare($sql);
$query-> bindParam(':username', $username, PDO::PARAM_STR);
$query-> bindParam(':password', $password, PDO::PARAM_STR);
$query-> execute();
$results = $query -> fetchAll(PDO::FETCH_OBJ);
if($query -> rowCount() > 0)
{
$con="update tbladmin set Password=:newpassword where UserName=:username";
$chngpwd1 = $dbh->prepare($con);
$chngpwd1-> bindParam(':username', $username, PDO::PARAM_STR);
$chngpwd1-> bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
$chngpwd1->execute();
$msg="Your Password succesfully changed";
}
else {
$error="Your current password is not valid.";	
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
    <meta name="theme-color" content="#3e454c">
    
    <title>Change Password | Life Savior</title>

    <!-- Favicon -->
    <link rel="icon" href="../admin/img/icon.jpg" type="image/jpeg">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <!-- Admin Style -->
    <link rel="stylesheet" href="css/style.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --warning: #f72585;
            --light: #f8f9fa;
            --dark: #212529;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
            color: #4a5568;
        }
        
        .page-title {
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid rgba(67, 97, 238, 0.2);
        }
        
        .form-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 2rem;
            margin-top: 1.5rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            border: none;
            text-align: center;
        }
        
        .form-label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.75rem 1rem 0.75rem 3rem;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(67, 97, 238, 0.25);
            width: 100%;
            max-width: 300px;
            margin: 0 auto;
            display: block;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(67, 97, 238, 0.35);
        }
        
        .errorWrap {
            padding: 1rem;
            margin: 0 0 1.5rem 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            border-radius: 0 8px 8px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        
        .succWrap {
            padding: 1rem;
            margin: 0 0 1.5rem 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            border-radius: 0 8px 8px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        
        .icon-container {
            display: flex;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        
        .icon-container i {
            font-size: 3rem;
            color: var(--primary);
            background: rgba(67, 97, 238, 0.1);
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .form-section {
            background: rgba(67, 97, 238, 0.03);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px dashed rgba(67, 97, 238, 0.2);
        }
        
        .form-section h5 {
            color: var(--primary);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid rgba(67, 97, 238, 0.1);
            text-align: center;
        }
        
        .required-star {
            color: #dd3d36;
            margin-left: 3px;
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
        
        .password-input-container {
            position: relative;
        }
        
        .password-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #718096;
            z-index: 10;
        }
        
        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #718096;
            cursor: pointer;
            z-index: 10;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        .password-guidelines {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1.5rem;
            border-left: 3px solid var(--primary);
        }
        
        .password-guidelines ul {
            padding-left: 1.5rem;
            margin-bottom: 0;
        }
        
        .password-guidelines li {
            margin-bottom: 0.5rem;
            color: #4a5568;
        }
        
        @media (max-width: 768px) {
            .form-container {
                padding: 1.5rem;
            }
            
            .password-icon {
                left: 0.8rem;
            }
            
            .password-toggle {
                right: 0.8rem;
            }
            
            .form-control {
                padding-left: 2.5rem;
            }
        }
    </style>
</head>

<body>
    <?php include('includes/header.php');?>

    <div class="ts-main-content">
        <?php include('includes/leftbar.php');?>
        <div class="content-wrapper">
            <div class="container-fluid pt-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-center mb-4">
                            <h2 class="page-title">Change Your Password</h2>
                        </div>
                        
                        <div class="form-container">
                            <div class="icon-container">
                                <i class="fas fa-lock"></i>
                            </div>
                            
                            <div class="row justify-content-center">
                                <div class="col-lg-10">
                                    <?php if($error){?><div class="errorWrap"><i class="fas fa-exclamation-circle me-2"></i><?php echo htmlentities($error); ?> </div><?php } 
                                    else if($msg){?><div class="succWrap"><i class="fas fa-check-circle me-2"></i><?php echo htmlentities($msg); ?> </div><?php }?>
                                    
                                    <div class="form-section">
                                        <h5><i class="fas fa-key me-2"></i>Update Your Password</h5>
                                        <form method="post" name="chngpwd" class="row g-3" onSubmit="return valid();">
                                            <div class="col-12">
                                                <label for="password" class="form-label">Current Password<span class="required-star">*</span></label>
                                                <div class="password-input-container">
                                                    <i class="fas fa-lock password-icon"></i>
                                                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter your current password" required>
                                                    <i class="fas fa-eye password-toggle" id="toggleCurrentPassword"></i>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12">
                                                <label for="newpassword" class="form-label">New Password<span class="required-star">*</span></label>
                                                <div class="password-input-container">
                                                    <i class="fas fa-key password-icon"></i>
                                                    <input type="password" class="form-control" name="newpassword" id="newpassword" placeholder="Create a strong new password" required>
                                                    <i class="fas fa-eye password-toggle" id="toggleNewPassword"></i>
                                                </div>
                                                <div class="password-strength">
                                                    <div class="password-strength-meter" id="passwordStrengthMeter"></div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12">
                                                <label for="confirmpassword" class="form-label">Confirm Password<span class="required-star">*</span></label>
                                                <div class="password-input-container">
                                                    <i class="fas fa-key password-icon"></i>
                                                    <input type="password" class="form-control" name="confirmpassword" id="confirmpassword" placeholder="Re-enter your new password" required>
                                                    <i class="fas fa-eye password-toggle" id="toggleConfirmPassword"></i>
                                                </div>
                                                <div id="passwordMatch" class="mt-2 small"></div>
                                            </div>
                                            
                                            <div class="col-12 mt-4">
                                                <button class="btn btn-primary" name="submit" type="submit">
                                                    <i class="fas fa-save me-2"></i>Update Password
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    <div class="password-guidelines">
                                        <h6><i class="fas fa-shield-alt me-2"></i>Password Security Guidelines</h6>
                                        <ul>
                                            <li>Use at least 8 characters</li>
                                            <li>Include uppercase and lowercase letters</li>
                                            <li>Include at least one number</li>
                                            <li>Include at least one special character (e.g., !@#$%^&*)</li>
                                            <li>Avoid common words or personal information</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
    
    <script>
        // Password toggle visibility
        $(document).ready(function() {
            // Toggle current password
            $('#toggleCurrentPassword').click(function() {
                const passwordInput = $('#password');
                const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
                passwordInput.attr('type', type);
                $(this).toggleClass('fa-eye fa-eye-slash');
            });
            
            // Toggle new password
            $('#toggleNewPassword').click(function() {
                const passwordInput = $('#newpassword');
                const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
                passwordInput.attr('type', type);
                $(this).toggleClass('fa-eye fa-eye-slash');
            });
            
            // Toggle confirm password
            $('#toggleConfirmPassword').click(function() {
                const passwordInput = $('#confirmpassword');
                const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
                passwordInput.attr('type', type);
                $(this).toggleClass('fa-eye fa-eye-slash');
            });
            
            // Password strength indicator
            $('#newpassword').on('input', function() {
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
            $('#confirmpassword').on('input', function() {
                const password = $('#newpassword').val();
                const confirmPassword = $(this).val();
                
                if (confirmPassword.length === 0) {
                    $('#passwordMatch').html('');
                    return;
                }
                
                if (password === confirmPassword) {
                    $('#passwordMatch').html('<i class="fas fa-check-circle text-success me-2"></i>Passwords match');
                } else {
                    $('#passwordMatch').html('<i class="fas fa-times-circle text-danger me-2"></i>Passwords do not match');
                }
            });
            
            // Form validation
            function valid() {
                if($('#newpassword').val() !== $('#confirmpassword').val()) {
                    alert("New Password and Confirm Password Field do not match  !!");
                    $('#confirmpassword').focus();
                    return false;
                }
                return true;
            }
            
            // Add animation to submit button
            $('button[name="submit"]').hover(
                function() {
                    $(this).css('transform', 'translateY(-2px)');
                },
                function() {
                    $(this).css('transform', 'translateY(0)');
                }
            );
        });
    </script>
</body>
</html>
<?php } ?>