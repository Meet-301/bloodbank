<?php
// Debugging setup - remove in production
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session management
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config.php';

// Verify database connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    error_log("Login attempt for username: $username"); // Debug logging
    
    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("SELECT admin_id, username, password FROM admins WHERE username = ?");
        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error);
            $error = "Database error occurred";
        } else {
            $stmt->bind_param("s", $username);
            
            if (!$stmt->execute()) {
                error_log("Execute failed: " . $stmt->error);
                $error = "Database error occurred";
            } else {
                $result = $stmt->get_result();
                
                if ($result->num_rows == 1) {
                    $admin = $result->fetch_assoc();
                    error_log("Retrieved hash: " . $admin['password']); // Debug
                    
                    if (password_verify($password, $admin['password'])) {
                        $_SESSION['admin_id'] = $admin['admin_id'];
                        $_SESSION['username'] = $admin['username'];
                        
                        // Debug session vars
                        error_log("Session set: admin_id=".$_SESSION['admin_id']);
                        
                        // Update last login
                        $update_stmt = $conn->prepare("UPDATE admins SET last_login = NOW() WHERE admin_id = ?");
                        $update_stmt->bind_param("i", $admin['admin_id']);
                        $update_stmt->execute();
                        $update_stmt->close();
                        
                        header("Location: dashboard.php");
                        exit();
                    } else {
                        $error = "Invalid username or password";
                        error_log("Password verification failed for $username");
                    }
                } else {
                    $error = "Invalid username or password";
                    error_log("No user found with username: $username");
                }
            }
            $stmt->close();
        }
    } else {
        $error = "Please enter both username and password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Bank | Admin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #d32f2f;
            --primary-dark: #b71c1c;
            --light: #f5f7fa;
            --dark: #333;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 400px;
            padding: 40px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header h1 {
            color: var(--primary);
            margin-bottom: 10px;
        }
        
        .login-header i {
            font-size: 50px;
            color: var(--primary);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
        }
        
        .btn {
            width: 100%;
            padding: 12px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .btn:hover {
            background: var(--primary-dark);
        }
        
        .error-message {
            color: var(--primary);
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-tint"></i>
            <h1>Blood Bank Admin</h1>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</body>
</html>