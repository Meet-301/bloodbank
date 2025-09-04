<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
{	
    header('location:index.php');
}
else{
    // Code for updating contact info
    if(isset($_POST['submit']))
    {
        $address = $_POST['address'];
        $email = $_POST['email'];	
        $contactno = $_POST['contactno'];
        
        $sql = "update tblcontactusinfo set Address=:address, EmailId=:email, ContactNo=:contactno";
        $query = $dbh->prepare($sql);
        $query->bindParam(':address',$address,PDO::PARAM_STR);
        $query->bindParam(':email',$email,PDO::PARAM_STR);
        $query->bindParam(':contactno',$contactno,PDO::PARAM_STR);
        $query->execute();
        
        if($query->rowCount() > 0) {
            $msg = "Contact information updated successfully!";
        } else {
            $error = "No changes were made to contact information.";
        }
    }
    
    // Fetch current contact info
    $sql = "SELECT * FROM tblcontactusinfo";
    $query = $dbh->prepare($sql);
    $query->execute();
    $contactInfo = $query->fetch(PDO::FETCH_OBJ);
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
    
    <title>BBDMS | Update Contact Info</title>

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
            --info: #4895ef;
            --warning: #f72585;
            --light: #f8f9fa;
            --dark: #212529;
            --sidebar-bg: #1a2236;
            --card-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
            color: #4a5568;
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        .page-title {
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid rgba(67, 97, 238, 0.2);
        }
        
        /* Sidebar styling */
        .sidebar {
            background: var(--sidebar-bg);
            width: 250px;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 3px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .sidebar-header {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-header h4 {
            color: white;
            font-weight: 700;
            margin-bottom: 0;
        }
        
        .sidebar-menu {
            padding: 1rem 0;
        }
        
        .sidebar-menu .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 0.75rem 1.5rem;
            margin: 0.25rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        
        .sidebar-menu .nav-link:hover, 
        .sidebar-menu .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        .sidebar-menu .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }
        
        /* Main content area */
        .main-content {
            flex: 1;
            margin-left: 250px;
            transition: all 0.3s ease;
        }
        
        /* Header styling */
        .top-header {
            background: white;
            padding: 1rem 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .user-info {
            display: flex;
            align-items: center;
        }
        
        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 0.75rem;
        }
        
        /* Card Styling */
        .dashboard-card {
            border-radius: 12px;
            border: none;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            margin-bottom: 1.5rem;
            background: white;
        }
        
        .card-header {
            border-bottom: none;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 1.25rem 1.5rem;
            border-radius: 12px 12px 0 0 !important;
        }
        
        .card-body {
            padding: 1.75rem 1.5rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select, .form-textarea {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus, .form-textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(67, 97, 238, 0.3);
        }
        
        .errorWrap {
            padding: 1rem;
            margin: 0 0 1.5rem 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            border-radius: 0 8px 8px 0;
            box-shadow: var(--card-shadow);
        }
        
        .succWrap {
            padding: 1rem;
            margin: 0 0 1.5rem 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            border-radius: 0 8px 8px 0;
            box-shadow: var(--card-shadow);
        }
        
        .content-wrapper {
            padding: 1.5rem;
        }
        
        .contact-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(67, 97, 238, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: var(--primary);
            font-size: 1.5rem;
        }
        
        .contact-info-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            padding: 1.5rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .contact-info-card h5 {
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }
        
        .contact-info-card p {
            color: #718096;
            margin-bottom: 0.25rem;
        }
        
        /* Footer styling */
        .app-footer {
            background: white;
            padding: 1rem 1.5rem;
            text-align: center;
            color: #718096;
            font-size: 0.9rem;
            border-top: 1px solid #e2e8f0;
        }
        
        .form-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
            z-index: 10;
        }
        
        .form-icon-wrapper {
            position: relative;
        }
        
        .form-icon-wrapper .form-control,
        .form-icon-wrapper .form-textarea {
            padding-left: 45px;
        }
        
        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .contact-map {
            height: 300px;
            background: #f0f4ff;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 1.5rem;
        }
        
        .contact-map i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h4>BBDMS Admin</h4>
        </div>
        <nav class="sidebar-menu">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="donor-list.php">
                        <i class="fas fa-user-friends"></i>
                        Donors
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="requests-received.php">
                        <i class="fas fa-heartbeat"></i>
                        Blood Requests
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage-bloodgroup.php">
                        <i class="fas fa-tint"></i>
                        Blood Groups
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage-pages.php">
                        <i class="fas fa-file-alt"></i>
                        Manage Pages
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage-conactusquery.php">
                        <i class="fas fa-question-circle"></i>
                        Support Queries
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="update-contact.php">
                        <i class="fas fa-address-book"></i>
                        Contact Info
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="change-password.php">
                        <i class="fas fa-lock"></i>
                        Change Password
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div>
                <h3 class="mb-0">Contact Information</h3>
                <p class="text-muted mb-0">Update your organization's contact details</p>
            </div>
            <div class="user-info">
                <img src="https://ui-avatars.com/api/?name=Admin&background=4361ee&color=fff" alt="Admin">
                <div>
                    <div class="fw-bold">Admin</div>
                    <small>Administrator</small>
                </div>
            </div>
        </header>
        
        <!-- Page Content -->
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row mb-4">
                    <div class="col">
                        <h2 class="page-title">Update Contact Information</h2>
                        <p class="text-muted">Manage how people can contact your organization</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-5">
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h5 class="mb-0">Current Contact Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="contact-info-card">
                                    <div class="contact-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <h5>Address</h5>
                                    <p><?php echo htmlentities($contactInfo->Address); ?></p>
                                </div>
                                
                                <div class="contact-info-card">
                                    <div class="contact-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <h5>Email Address</h5>
                                    <p><?php echo htmlentities($contactInfo->EmailId); ?></p>
                                </div>
                                
                                <div class="contact-info-card">
                                    <div class="contact-icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <h5>Contact Number</h5>
                                    <p><?php echo htmlentities($contactInfo->ContactNo); ?></p>
                                </div>
                                
                                <div class="contact-map">
                                    <div class="text-center">
                                        <i class="fas fa-map-marked-alt"></i>
                                        <div>Map Location Preview</div>
                                        <small class="text-muted">Address will appear on maps</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-7">
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h5 class="mb-0">Update Contact Information</h5>
                            </div>
                            <div class="card-body">
                                <?php if(isset($error)): ?>
                                    <div class="errorWrap"><strong>ERROR</strong>: <?php echo htmlentities($error); ?></div>
                                <?php elseif(isset($msg)): ?>
                                    <div class="succWrap"><strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?></div>
                                <?php endif; ?>
                                
                                <form method="post" class="form-horizontal">
                                    <div class="mb-4">
                                        <label class="form-label">Organization Address</label>
                                        <div class="form-icon-wrapper">
                                            <i class="fas fa-map-marker-alt form-icon"></i>
                                            <textarea class="form-control form-textarea" name="address" id="address" required><?php echo htmlentities($contactInfo->Address); ?></textarea>
                                        </div>
                                        <small class="text-muted">This address will appear on the contact page and in Google Maps</small>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label">Email Address</label>
                                        <div class="form-icon-wrapper">
                                            <i class="fas fa-envelope form-icon"></i>
                                            <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlentities($contactInfo->EmailId); ?>" required>
                                        </div>
                                        <small class="text-muted">This is the primary email for contact inquiries</small>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label">Contact Number</label>
                                        <div class="form-icon-wrapper">
                                            <i class="fas fa-phone form-icon"></i>
                                            <input type="text" class="form-control" value="<?php echo htmlentities($contactInfo->ContactNo); ?>" name="contactno" id="contactno" required>
                                        </div>
                                        <small class="text-muted">Include country code if applicable (e.g., +1 for US)</small>
                                    </div>
                                    
                                    <div class="text-end mt-4">
                                        <button class="btn btn-primary btn-lg" name="submit" type="submit">
                                            <i class="fas fa-save me-2"></i>Update Contact Info
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <div class="dashboard-card mt-4">
                            <div class="card-header" style="background: linear-gradient(135deg, #4cc9f0, #4895ef);">
                                <h5 class="mb-0">Contact Information Best Practices</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-4 mb-md-0">
                                        <div class="d-flex align-items-start">
                                            <div class="p-3 rounded-circle me-3" style="background: rgba(67, 97, 238, 0.1); color: #4361ee;">
                                                <i class="fas fa-map-marked-alt"></i>
                                            </div>
                                            <div>
                                                <h6>Complete Address</h6>
                                                <p class="mb-0">Include street, city, state, and postal code for better map accuracy.</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start">
                                            <div class="p-3 rounded-circle me-3" style="background: rgba(76, 201, 240, 0.1); color: #4cc9f0;">
                                                <i class="fas fa-phone-volume"></i>
                                            </div>
                                            <div>
                                                <h6>Phone Availability</h6>
                                                <p class="mb-0">Specify contact hours if phone support is limited to certain times.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-4">
                                    <div class="col-md-6 mb-4 mb-md-0">
                                        <div class="d-flex align-items-start">
                                            <div class="p-3 rounded-circle me-3" style="background: rgba(247, 37, 133, 0.1); color: #f72585;">
                                                <i class="fas fa-mail-bulk"></i>
                                            </div>
                                            <div>
                                                <h6>Email Management</h6>
                                                <p class="mb-0">Ensure emails are monitored daily for timely responses.</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start">
                                            <div class="p-3 rounded-circle me-3" style="background: rgba(67, 97, 238, 0.1); color: #4361ee;">
                                                <i class="fas fa-sync-alt"></i>
                                            </div>
                                            <div>
                                                <h6>Regular Updates</h6>
                                                <p class="mb-0">Review contact info quarterly to ensure accuracy.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <footer class="app-footer mt-4">
                    <div class="container">
                        <p>© 2023 Blood Bank & Donation Management System. All rights reserved.</p>
                    </div>
                </footer>
            </div>
        </div>
    </main>

    <!-- Loading Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
    
    <script>
        // Add character counter for textarea
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('address');
            const counter = document.createElement('div');
            counter.className = 'text-end text-muted small mt-1';
            counter.innerHTML = '<span id="char-count">0</span> characters';
            textarea.parentNode.insertBefore(counter, textarea.nextSibling);
            
            textarea.addEventListener('input', function() {
                document.getElementById('char-count').textContent = textarea.value.length;
            });
            
            // Trigger initial count
            document.getElementById('char-count').textContent = textarea.value.length;
        });
    </script>
</body>
</html>
<?php } ?>