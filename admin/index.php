<?php
session_start();
include('includes/config.php');
if(isset($_POST['login']))
{
$username=$_POST['username'];
$password=($_POST['password']);
$sql ="SELECT UserName,Password FROM tbladmin WHERE UserName=:username and Password=:password";
$query= $dbh -> prepare($sql);
$query-> bindParam(':username', $username, PDO::PARAM_STR);
$query-> bindParam(':password', $password, PDO::PARAM_STR);
$query-> execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
if($query->rowCount() > 0)
{
$_SESSION['alogin']=$_POST['username'];
echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
} else{
  
  echo "<script>alert('Invalid Details');</script>";

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

	<title>Admin Login | Life Savior</title>

	<!-- Favicon -->
    <link rel="icon" href="../admin/img/icon.jpg" type="image/jpeg">
	
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
		
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
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
			animation: animate 5s linear infinite;
			bottom: -150px;
		}
		
		.blood-drop:nth-child(1) {
			left: 25%;
			animation-delay: 0s;
		}
		
		.blood-drop:nth-child(2) {
			left: 10%;
			animation-delay: 2s;
			animation-duration: 12s;
		}
		
		.blood-drop:nth-child(3) {
			left: 70%;
			animation-delay: 4s;
		}
		
		.blood-drop:nth-child(4) {
			left: 40%;
			animation-delay: 0s;
			animation-duration: 18s;
		}
		
		.blood-drop:nth-child(5) {
			left: 65%;
			animation-delay: 0s;
		}
		
		.blood-drop:nth-child(6) {
			left: 75%;
			animation-delay: 3s;
		}
		
		.blood-drop:nth-child(7) {
			left: 35%;
			animation-delay: 7s;
		}
		
		.blood-drop:nth-child(8) {
			left: 50%;
			animation-delay: 15s;
			animation-duration: 45s;
		}
		
		.blood-drop:nth-child(9) {
			left: 20%;
			animation-delay: 2s;
			animation-duration: 35s;
		}
		
		.blood-drop:nth-child(10) {
			left: 85%;
			animation-delay: 0s;
			animation-duration: 11s;
		}
		
		@keyframes animate {
			0% {
				transform: translateY(0) rotate(-45deg);
				opacity: 1;
				border-radius: 50% 50% 50% 0;
			}
			
			70% {
				opacity: 0.8;
			}
			
			100% {
				transform: translateY(-1000px) rotate(-45deg);
				opacity: 0;
				border-radius: 50%;
			}
		}
		
		.login-container {
			width: 100%;
			max-width: 500px;
			background: rgba(30, 30, 40, 0.85);
			backdrop-filter: blur(10px);
			border-radius: 16px;
			overflow: hidden;
			box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
			border: 1px solid rgba(255, 255, 255, 0.1);
			position: relative;
			z-index: 1;
		}
		
		.login-header {
			text-align: center;
			padding: 30px 20px;
			background: rgba(198, 10, 10, 0.2);
			border-bottom: 2px solid var(--primary);
			position: relative;
		}
		
		.login-header::before {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23c20a0a" fill-opacity="0.1" d="M0,192L48,197.3C96,203,192,213,288,229.3C384,245,480,267,576,261.3C672,256,768,224,864,197.3C960,171,1056,149,1152,154.7C1248,160,1344,192,1392,208L1440,224L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
			background-size: cover;
			background-position: center;
			opacity: 0.5;
		}
		
		.login-header h1 {
			font-weight: 600;
			font-size: 28px;
			margin-bottom: 8px;
			color: white;
			position: relative;
			text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
		}
		
		.login-header p {
			color: rgba(255, 255, 255, 0.8);
			font-size: 16px;
			margin-bottom: 0;
			font-weight: 300;
		}
		
		.login-icon {
			width: 80px;
			height: 80px;
			background: var(--primary);
			border-radius: 50%;
			display: flex;
			align-items: center;
			justify-content: center;
			margin: 0 auto 20px;
			font-size: 36px;
			color: white;
			box-shadow: 0 5px 15px rgba(198, 10, 10, 0.4);
		}
		
		.login-body {
			padding: 30px;
		}
		
		.form-group {
			margin-bottom: 25px;
			position: relative;
		}
		
		.form-control {
			width: 100%;
			height: 50px;
			background: rgba(255, 255, 255, 0.08);
			border: 1px solid rgba(255, 255, 255, 0.15);
			border-radius: 8px;
			padding: 15px 20px 0;
			color: white;
			font-size: 16px;
			transition: all 0.3s ease;
		}
		
		.form-control:focus {
			background: rgba(255, 255, 255, 0.12);
			border-color: var(--primary);
			outline: none;
			box-shadow: 0 0 0 3px rgba(198, 10, 10, 0.2);
		}
		
		.form-label {
			position: absolute;
			top: 16px;
			left: 20px;
			color: rgba(255, 255, 255, 0.7);
			pointer-events: none;
			transition: all 0.3s ease;
			font-size: 16px;
		}
		
		.form-control:focus + .form-label,
		.form-control:not(:placeholder-shown) + .form-label {
			top: 6px;
			font-size: 12px;
			color: var(--primary);
		}
		
		.password-container {
			position: relative;
		}
		
		.password-toggle {
			position: absolute;
			right: 15px;
			top: 15px;
			color: rgba(255, 255, 255, 0.6);
			cursor: pointer;
			z-index: 10;
		}
		
		.btn-login {
			width: 100%;
			height: 50px;
			background: var(--primary);
			border: none;
			border-radius: 8px;
			color: white;
			font-size: 16px;
			font-weight: 500;
			cursor: pointer;
			transition: all 0.3s ease;
			margin-top: 10px;
		}
		
		.btn-login:hover {
			background: var(--primary-dark);
			transform: translateY(-2px);
			box-shadow: 0 5px 15px rgba(198, 10, 10, 0.4);
		}
		
		.btn-login:active {
			transform: translateY(0);
		}
		
		.forgot-password {
			display: block;
			text-align: right;
			color: rgba(255, 255, 255, 0.7);
			margin-top: 10px;
			text-decoration: none;
			font-size: 14px;
			transition: color 0.3s ease;
		}
		
		.forgot-password:hover {
			color: var(--primary);
		}
		
		.footer-links {
			display: flex;
			justify-content: space-between;
			margin-top: 25px;
			padding-top: 20px;
			border-top: 1px solid rgba(255, 255, 255, 0.1);
		}
		
		.home-link {
			display: inline-flex;
			align-items: center;
			color: rgba(255, 255, 255, 0.7);
			text-decoration: none;
			font-size: 14px;
			transition: color 0.3s ease;
		}
		
		.home-link:hover {
			color: var(--primary);
		}
		
		.home-link i {
			margin-right: 8px;
		}
		
		.copyright {
			color: rgba(255, 255, 255, 0.5);
			font-size: 13px;
			text-align: center;
			margin-top: 20px;
		}
		
		@media (max-width: 576px) {
			.login-container {
				border-radius: 12px;
			}
			
			.login-header {
				padding: 25px 15px;
			}
			
			.login-header h1 {
				font-size: 24px;
			}
			
			.login-body {
				padding: 25px 20px;
			}
			
			.footer-links {
				flex-direction: column;
				gap: 15px;
			}
		}
	</style>
</head>

<body>
	<!-- Animated background elements -->
	<div class="blood-drops">
		<div class="blood-drop"></div>
		<div class="blood-drop"></div>
		<div class="blood-drop"></div>
		<div class="blood-drop"></div>
		<div class="blood-drop"></div>
		<div class="blood-drop"></div>
		<div class="blood-drop"></div>
		<div class="blood-drop"></div>
		<div class="blood-drop"></div>
		<div class="blood-drop"></div>
	</div>
	
	<!-- Login Container -->
	<div class="login-container">
		<div class="login-header">
			<div class="login-icon">
				<i class="fas fa-tint"></i>
			</div>
			<h1>BloodBank & Donor Management System</h1>
			<p>Administrator Access Portal</p>
		</div>
		
		<div class="login-body">
			<form method="post">
				<div class="form-group">
					<input type="text" class="form-control" id="username" name="username" placeholder=" " required>
					<label for="username" class="form-label">
						<i class="fas fa-user me-2"></i> Username
					</label>
				</div>
				
				<div class="form-group password-container">
					<input type="password" class="form-control" id="password" name="password" placeholder=" " required>
					<label for="password" class="form-label">
						<i class="fas fa-lock me-2"></i> Password
					</label>
					<span class="password-toggle" id="togglePassword">
						<i class="fas fa-eye"></i>
					</span>
				</div>
				
				<a href="forgot-password.php" class="forgot-password">
					<i class="fas fa-key me-2"></i> Forgot Password?
				</a>
				
				<button class="btn-login" name="login" type="submit">
					<i class="fas fa-sign-in-alt me-2"></i> LOGIN
				</button>
			</form>
			
			<div class="footer-links">
				<a href="../index.php" class="home-link">
					<i class="fas fa-home me-2"></i>Back to Home
				</a>
				
				<div class="support">
					<i class="fas fa-headset me-2"></i>
					<span>Support: admin@bloodbank.org</span>
				</div>
			</div>
			
			<div class="copyright">
				&copy; 2025 BloodBank & Donor Management System. All rights reserved.
			</div>
		</div>
	</div>
	
	<script>
		// Password toggle functionality
		const togglePassword = document.querySelector('#togglePassword');
		const password = document.querySelector('#password');
		
		togglePassword.addEventListener('click', function() {
			// Toggle password visibility
			const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
			password.setAttribute('type', type);
			
			// Toggle eye icon
			this.querySelector('i').classList.toggle('fa-eye');
			this.querySelector('i').classList.toggle('fa-eye-slash');
		});
		
		// Add floating label functionality
		const inputs = document.querySelectorAll('.form-control');
		inputs.forEach(input => {
			// Add focus event
			input.addEventListener('focus', function() {
				this.nextElementSibling.style.color = '#c20a0a';
			});
			
			// Add blur event
			input.addEventListener('blur', function() {
				if (!this.value) {
					this.nextElementSibling.style.color = 'rgba(255, 255, 255, 0.7)';
				}
			});
			
			// Initialize on page load
			if (input.value) {
				input.nextElementSibling.style.top = '6px';
				input.nextElementSibling.style.fontSize = '12px';
			}
		});
	</script>
</body>

</html>