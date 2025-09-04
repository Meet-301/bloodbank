<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
{	
header('location:index.php');
}
else{
	
    // Initialize variables
    $error = '';
    $msg = '';

    // Form submission handling
    if(isset($_POST['submit']))
    {
        $pagetype = $_GET['type'] ?? '';
        $pgedetails = $_POST['pgedetails'] ?? '';

        if(!empty($pagetype)) {
            // Update page content in database
            $sql = "UPDATE tblpages SET detail = :pgedetails WHERE type = :pagetype";
            $query = $dbh->prepare($sql);
            $query->bindParam(':pagetype', $pagetype, PDO::PARAM_STR);
            $query->bindParam(':pgedetails', $pgedetails, PDO::PARAM_STR);
            $query->execute();
            
            if($query->rowCount() > 0) {
                $msg = "Page content updated successfully";
            } else {
                $error = "No changes made or page not found";
            }
        } else {
            $error = "Page type is missing. Please select a page to update.";
        }
    }
?>
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
    
    <title>BBDMS | Manage Pages</title>

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
        
        .form-control, .form-select {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
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
        
        .page-type-card {
            cursor: pointer;
            border-radius: 8px;
            padding: 1.25rem;
            background: white;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            border: 2px solid transparent;
            text-align: center;
        }
        
        .page-type-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }
        
        .page-type-card.active {
            border-color: var(--primary);
            background: rgba(67, 97, 238, 0.05);
        }
        
        .page-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--primary);
            background: rgba(67, 97, 238, 0.1);
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto 1.5rem;
        }
        
        .page-type-name {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        
        .page-type-desc {
            color: #718096;
            font-size: 0.9rem;
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
                    <a class="nav-link active" href="manage-pages.php">
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
                <h3 class="mb-0">Manage Pages</h3>
                <p class="text-muted mb-0">Edit website content pages</p>
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
                        <h2 class="page-title">Manage Website Pages</h2>
                        <p class="text-muted">Edit and update your website's content pages</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h5 class="mb-0">Page Editor</h5>
                            </div>
                            <div class="card-body">
                                <?php if(isset($error)){ ?>
                                    <div class="errorWrap"><strong>ERROR</strong>: <?php echo htmlentities($error); ?> </div>
                                <?php } 
                                else if(isset($msg)){ ?>
                                    <div class="succWrap"><strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?> </div>
                                <?php } ?>
                                
                                <form method="post" name="chngpwd" class="form-horizontal">
                                    <div class="row mb-4">
                                        <div class="col-md-12">
                                            <label class="form-label">Select Page to Edit</label>
                                            
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <div class="page-type-card" onclick="window.location='manage-pages.php?type=aboutus'">
                                                        <div class="page-icon">
                                                            <i class="fas fa-info-circle"></i>
                                                        </div>
                                                        <div class="page-type-name">About Us</div>
                                                        <div class="page-type-desc">Company information and mission</div>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-3">
                                                    <div class="page-type-card" onclick="window.location='manage-pages.php?type=donor'">
                                                        <div class="page-icon">
                                                            <i class="fas fa-hand-holding-heart"></i>
                                                        </div>
                                                        <div class="page-type-name">Why Become Donor</div>
                                                        <div class="page-type-desc">Benefits and process of donation</div>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-3">
                                                    <div class="page-type-card" onclick="window.location='manage-pages.php?type=terms'">
                                                        <div class="page-icon">
                                                            <i class="fas fa-file-contract"></i>
                                                        </div>
                                                        <div class="page-type-name">Terms & Conditions</div>
                                                        <div class="page-type-desc">Legal terms for using our services</div>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-3">
                                                    <div class="page-type-card" onclick="window.location='manage-pages.php?type=privacy'">
                                                        <div class="page-icon">
                                                            <i class="fas fa-user-shield"></i>
                                                        </div>
                                                        <div class="page-type-name">Privacy Policy</div>
                                                        <div class="page-type-desc">How we handle your personal data</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-4">
                                        <div class="col-md-12">
                                            <label class="form-label">Currently Selected Page</label>
                                            <div class="selected-page-display">
                                                <?php
                                                if(isset($_GET['type'])) {
                                                    switch($_GET['type']) {
                                                        case "terms":
                                                            echo "<i class='fas fa-file-contract me-2'></i> Terms and Conditions";
                                                            break;
                                                        case "privacy":
                                                            echo "<i class='fas fa-user-shield me-2'></i> Privacy And Policy";
                                                            break;
                                                        case "aboutus":
                                                            echo "<i class='fas fa-info-circle me-2'></i> About Us";
                                                            break;
                                                        case "donor":
                                                            echo "<i class='fas fa-hand-holding-heart me-2'></i> Why Become Donor";
                                                            break;
                                                        case "faqs":
                                                            echo "<i class='fas fa-question-circle me-2'></i> FAQs";
                                                            break;
                                                        default:
                                                            echo "No page selected";
                                                    }
                                                } else {
                                                    echo "No page selected";
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-4">
                                        <div class="col-md-12">
                                            <label class="form-label">Page Content</label>
                                            <div class="editor-container">
                                                <textarea class="form-control" rows="10" name="pgedetails" id="pgedetails" placeholder="Page content" required>
    <?php 
    if(isset($_GET['type'])) {
        $pagetype=$_GET['type'];
        $sql = "SELECT detail from tblpages where type=:pagetype";
        $query = $dbh->prepare($sql);
        $query->bindParam(':pagetype',$pagetype,PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        if($result) {
            echo htmlspecialchars($result->detail);
        }
    }
    ?>
</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12 text-end">
                                            <button type="submit" name="submit" value="Update" class="btn btn-primary btn-lg">
                                                <i class="fas fa-save me-2"></i>Update Page Content
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="dashboard-card">
                            <div class="card-header" style="background: linear-gradient(135deg, #4cc9f0, #4895ef);">
                                <h5 class="mb-0">Page Management Tips</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start mb-4">
                                            <div class="p-3 rounded-circle me-3" style="background: rgba(67, 97, 238, 0.1); color: #4361ee;">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                            <div>
                                                <h6>Best Practices</h6>
                                                <p class="mb-0">Keep content concise and focused. Use headings to structure information for better readability.</p>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex align-items-start mb-4">
                                            <div class="p-3 rounded-circle me-3" style="background: rgba(76, 201, 240, 0.1); color: #4cc9f0;">
                                                <i class="fas fa-sync-alt"></i>
                                            </div>
                                            <div>
                                                <h6>Regular Updates</h6>
                                                <p class="mb-0">Review and update your content quarterly to ensure information remains accurate and relevant.</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start mb-4">
                                            <div class="p-3 rounded-circle me-3" style="background: rgba(247, 37, 133, 0.1); color: #f72585;">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </div>
                                            <div>
                                                <h6>SEO Considerations</h6>
                                                <p class="mb-0">Include relevant keywords naturally in your content to improve search engine visibility.</p>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex align-items-start">
                                            <div class="p-3 rounded-circle me-3" style="background: rgba(67, 97, 238, 0.1); color: #4361ee;">
                                                <i class="fas fa-link"></i>
                                            </div>
                                            <div>
                                                <h6>Internal Linking</h6>
                                                <p class="mb-0">Link to related pages within your content to help visitors navigate your site more effectively.</p>
                                            </div>
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
    </main>

    <!-- Loading Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/42igeppxhdayts1kove3n00jz29o8xrzzjf6xu29g6fa9a18/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="js/main.js"></script>
    
    <script>
        // Initialize rich text editor
        tinymce.init({
            selector: '#pgedetails',
            plugins: 'link lists image code',
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
            menubar: false,
            height: 400,
            content_style: 'body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; font-size: 14px; }'
        });
        
        // Update selected page card styling
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const pageType = urlParams.get('type');
            
            if (pageType) {
                const cards = document.querySelectorAll('.page-type-card');
                cards.forEach(card => {
                    const link = card.getAttribute('onclick');
                    if (link.includes(pageType)) {
                        card.classList.add('active');
                    }
                });
            }
        });
    </script>
</body>
</html>
<?php } ?>