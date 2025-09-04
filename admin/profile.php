<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    // Code for updating profile
    if (isset($_POST['submit'])) {
        $adminid = $_SESSION['alogin'];
        $AName = $_POST['adminname'];
        $mobno = $_POST['mobilenumber'];
        $email = $_POST['email'];

        $sql = "update tbladmin set AdminName=:adminname, MobileNumber=:mobilenumber, Email=:email where UserName=:aid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':adminname', $AName, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':mobilenumber', $mobno, PDO::PARAM_STR);
        $query->bindParam(':aid', $adminid, PDO::PARAM_STR);
        $query->execute();

        if ($query->rowCount() > 0) {
            $msg = "Your profile has been updated successfully!";
        } else {
            $error = "No changes were made to your profile.";
        }
    }

    // Fetch admin details
    $adminid = $_SESSION['alogin'];
    $sql = "SELECT * FROM tbladmin WHERE UserName = :aid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':aid', $adminid, PDO::PARAM_STR);
    $query->execute();
    $admin = $query->fetch(PDO::FETCH_OBJ);
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

        <title>Admin Profile | Life Savior</title>

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
                --info: #4895ef;
                --warning: #f72585;
                --light: #f8f9fa;
                --dark: #212529;
                --sidebar-bg: #1a2236;
                --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
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

            .form-control,
            .form-select {
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                padding: 0.75rem 1rem;
                font-size: 1rem;
                transition: all 0.3s ease;
            }

            .form-control:focus,
            .form-select:focus {
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

            .btn-outline-primary {
                border-color: var(--primary);
                color: var(--primary);
                font-weight: 600;
            }

            .btn-outline-primary:hover {
                background: var(--primary);
                color: white;
            }

            .selected-page-display {
                background-color: rgba(67, 97, 238, 0.1);
                border-radius: 8px;
                padding: 0.75rem 1rem;
                font-weight: 600;
                color: var(--primary);
            }

            .editor-container {
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                padding: 1rem;
                background: white;
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

            .profile-avatar {
                width: 120px;
                height: 120px;
                border-radius: 50%;
                object-fit: cover;
                border: 4px solid white;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                margin: 0 auto 1.5rem;
                display: block;
            }

            .profile-info-card {
                background: white;
                border-radius: 12px;
                box-shadow: var(--card-shadow);
                padding: 1.5rem;
                text-align: center;
            }

            .profile-info-card h4 {
                font-weight: 700;
                margin-bottom: 0.5rem;
                color: var(--dark);
            }

            .profile-info-card p {
                color: #718096;
                margin-bottom: 0.25rem;
            }

            .info-badge {
                display: inline-block;
                background: rgba(67, 97, 238, 0.1);
                color: var(--primary);
                border-radius: 20px;
                padding: 0.25rem 0.75rem;
                font-size: 0.85rem;
                font-weight: 600;
                margin-top: 0.5rem;
            }

            .content-wrapper {
                padding: 1.5rem;
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

            .profile-form-row {
                margin-bottom: 1.5rem;
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

            .form-icon-wrapper .form-control {
                padding-left: 45px;
            }
        </style>
    </head>

    <body>
        <?php include('includes/leftbar.php'); ?>


        <!-- Main Content -->
        <main class="main-content">
            <?php include('includes/header.php'); ?>



            <!-- Page Content -->
            <div class="content-wrapper">
                <div class="container-fluid">
                    <div class="row mb-4">
                        <div class="col">
                            <h2 class="page-title">Admin Profile</h2>
                            <p class="text-muted">Update your personal information and account settings</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="dashboard-card mb-4">
                                <div class="card-body text-center">
                                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($admin->AdminName); ?>&background=4361ee&color=fff&size=200"
                                        alt="Admin Avatar" class="profile-avatar">
                                    <h4><?php echo htmlentities($admin->AdminName); ?></h4>
                                    <p class="text-muted">System Administrator</p>

                                    <div class="mt-4">
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <i class="fas fa-user-circle me-2 text-primary"></i>
                                            <span><?php echo htmlentities($admin->UserName); ?></span>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <i class="fas fa-envelope me-2 text-primary"></i>
                                            <span><?php echo htmlentities($admin->Email); ?></span>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="fas fa-phone me-2 text-primary"></i>
                                            <span><?php echo htmlentities($admin->MobileNumber); ?></span>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <span class="info-badge">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            Joined <?php echo date('d M Y', strtotime($admin->AdminRegdate)); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="dashboard-card">
                                <div class="card-header">
                                    <h5 class="mb-0">Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="dashboard.php" class="btn btn-outline-primary">
                                            <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                                        </a>
                                        <a href="change-password.php" class="btn btn-outline-primary">
                                            <i class="fas fa-lock me-2"></i>Change Password
                                        </a>
                                        <a href="logout.php" class="btn btn-outline-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="dashboard-card">
                                <div class="card-header">
                                    <h5 class="mb-0">Edit Profile Information</h5>
                                </div>
                                <div class="card-body">
                                    <?php if (isset($error)) { ?>
                                        <div class="errorWrap"><strong>ERROR</strong>: <?php echo htmlentities($error); ?>
                                        </div>
                                    <?php } else if (isset($msg)) { ?>
                                            <div class="succWrap"><strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?> </div>
                                    <?php } ?>

                                    <form method="post" class="form-horizontal">
                                        <div class="row profile-form-row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Admin Name</label>
                                                <div class="form-icon-wrapper">
                                                    <i class="fas fa-user form-icon"></i>
                                                    <input type="text" name="adminname"
                                                        value="<?php echo htmlentities($admin->AdminName); ?>"
                                                        class="form-control" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Username</label>
                                                <div class="form-icon-wrapper">
                                                    <i class="fas fa-at form-icon"></i>
                                                    <input type="text" name="username"
                                                        value="<?php echo htmlentities($admin->UserName); ?>"
                                                        class="form-control" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Contact Number</label>
                                                <div class="form-icon-wrapper">
                                                    <i class="fas fa-mobile-alt form-icon"></i>
                                                    <input type="text" name="mobilenumber"
                                                        value="<?php echo htmlentities($admin->MobileNumber); ?>"
                                                        class="form-control" maxlength="10" required pattern="[0-9]+">
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Email Address</label>
                                                <div class="form-icon-wrapper">
                                                    <i class="fas fa-envelope form-icon"></i>
                                                    <input type="email" name="email"
                                                        value="<?php echo htmlentities($admin->Email); ?>"
                                                        class="form-control" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Registration Date</label>
                                                <div class="form-icon-wrapper">
                                                    <i class="fas fa-calendar-alt form-icon"></i>
                                                    <input type="text"
                                                        value="<?php echo date('d M Y', strtotime($admin->AdminRegdate)); ?>"
                                                        class="form-control" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Account Status</label>
                                                <div class="form-icon-wrapper">
                                                    <i class="fas fa-check-circle form-icon"></i>
                                                    <input type="text" value="Active" class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 text-end">
                                                <button type="submit" name="submit" class="btn btn-primary btn-lg">
                                                    <i class="fas fa-save me-2"></i>Update Profile
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="dashboard-card mt-4">
                                <div class="card-header" style="background: linear-gradient(135deg, #4cc9f0, #4895ef);">
                                    <h5 class="mb-0">Security Tips</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-start mb-4">
                                                <div class="p-3 rounded-circle me-3"
                                                    style="background: rgba(67, 97, 238, 0.1); color: #4361ee;">
                                                    <i class="fas fa-lock"></i>
                                                </div>
                                                <div>
                                                    <h6>Password Security</h6>
                                                    <p class="mb-0">Use a strong, unique password and change it regularly to
                                                        protect your account.</p>
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-start">
                                                <div class="p-3 rounded-circle me-3"
                                                    style="background: rgba(76, 201, 240, 0.1); color: #4cc9f0;">
                                                    <i class="fas fa-user-shield"></i>
                                                </div>
                                                <div>
                                                    <h6>Account Activity</h6>
                                                    <p class="mb-0">Regularly review your account activity and log out after
                                                        each session.</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="d-flex align-items-start mb-4">
                                                <div class="p-3 rounded-circle me-3"
                                                    style="background: rgba(247, 37, 133, 0.1); color: #f72585;">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                </div>
                                                <div>
                                                    <h6>Phishing Awareness</h6>
                                                    <p class="mb-0">Be cautious of suspicious emails or links asking for
                                                        your credentials.</p>
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-start">
                                                <div class="p-3 rounded-circle me-3"
                                                    style="background: rgba(67, 97, 238, 0.1); color: #4361ee;">
                                                    <i class="fas fa-mobile-alt"></i>
                                                </div>
                                                <div>
                                                    <h6>Two-Factor Authentication</h6>
                                                    <p class="mb-0">Consider enabling two-factor authentication for enhanced
                                                        security.</p>
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
            // Add form validation
            document.addEventListener('DOMContentLoaded', function () {
                const form = document.querySelector('form');

                form.addEventListener('submit', function (e) {
                    const mobileInput = document.querySelector('input[name="mobilenumber"]');
                    const emailInput = document.querySelector('input[name="email"]');
                    let isValid = true;

                    // Mobile validation
                    if (!/^\d{10}$/.test(mobileInput.value)) {
                        mobileInput.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        mobileInput.classList.remove('is-invalid');
                    }

                    // Email validation
                    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
                        emailInput.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        emailInput.classList.remove('is-invalid');
                    }

                    if (!isValid) {
                        e.preventDefault();
                        alert('Please correct the highlighted fields.');
                    }
                });
            });
        </script>
    </body>

    </html>
<?php } ?>