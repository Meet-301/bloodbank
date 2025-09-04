<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
{	
    header('location:index.php');
}
else{
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
    
    <title>BBDMS | Donor List</title>

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
        
        /* Search Form Styling */
        .search-form-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .search-form-card h5 {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        /* Table Styling */
        .data-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .data-table thead th {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary);
            font-weight: 600;
            padding: 0.75rem 1rem;
            text-align: left;
        }
        
        .data-table tbody td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .data-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .data-table tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        .data-table tbody tr:nth-child(even) {
            background-color: #f9fafc;
        }
        
        .data-table tbody tr:hover {
            background-color: #f0f4ff;
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .badge-active {
            background-color: rgba(76, 201, 240, 0.1);
            color: #4cc9f0;
        }
        
        .badge-inactive {
            background-color: rgba(221, 61, 54, 0.1);
            color: #dd3d36;
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
        
        .no-results {
            text-align: center;
            padding: 2rem;
            color: #718096;
        }
        
        .no-results i {
            font-size: 3rem;
            color: #cbd5e0;
            margin-bottom: 1rem;
        }
        
        .search-icon-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--primary);
            font-size: 1.25rem;
        }
        
        .search-form-wrapper {
            position: relative;
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
                    <a class="nav-link active" href="donor-list.php">
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
                <h3 class="mb-0">Donor Management</h3>
                <p class="text-muted mb-0">View and search donor information</p>
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
                        <h2 class="page-title">Donor List</h2>
                        <p class="text-muted">Search and view donor information and blood requests</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h5 class="mb-0">Search Donors</h5>
                            </div>
                            <div class="card-body">
                                <div class="search-form-card">
                                    <form method="post" name="search" class="row g-3 align-items-center">
                                        <div class="col-md-8">
                                            <div class="search-form-wrapper">
                                                <input type="text" class="form-control form-control-lg" name="searchdata" id="searchdata" placeholder="Search by donor name or phone number" required>
                                                <button type="submit" name="search" class="search-icon-btn">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <button class="btn btn-primary btn-lg w-100" name="search" type="submit">
                                                <i class="fas fa-search me-2"></i>Search Donors
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                
                                <?php if(isset($_POST['search'])): 
                                $sdata = $_POST['searchdata'];
                                ?>
                                <div class="alert alert-primary mb-4">
                                    <i class="fas fa-info-circle me-2"></i> Showing results for: <strong><?php echo htmlentities($sdata); ?></strong>
                                </div>
                                
                                <div class="dashboard-card">
                                    <div class="card-header" style="background: linear-gradient(135deg, #4cc9f0, #4895ef);">
                                        <h5 class="mb-0">Blood Request Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="data-table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Donor Name</th>
                                                        <th>Donor Contact</th>
                                                        <th>Requirer Name</th>
                                                        <th>Requirer Contact</th>
                                                        <th>Requirer Email</th>
                                                        <th>Blood Required For</th>
                                                        <th>Message</th>
                                                        <th>Apply Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sql = "SELECT tblbloodrequirer.BloodDonarID,tblbloodrequirer.name,tblbloodrequirer.EmailId,tblbloodrequirer.ContactNumber,tblbloodrequirer.BloodRequirefor,tblbloodrequirer.Message,tblbloodrequirer.ApplyDate,tblblooddonars.id as donid,tblblooddonars.FullName,tblblooddonars.MobileNumber from  tblbloodrequirer join tblblooddonars on tblblooddonars.id=tblbloodrequirer.BloodDonarID where tblblooddonars.FullName like '%$sdata%' || tblblooddonars.MobileNumber like '%$sdata%'";
                                                    $query = $dbh->prepare($sql);
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                    $cnt = 1;
                                                    
                                                    if($query->rowCount() > 0) {
                                                        foreach($results as $row) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo htmlentities($cnt); ?></td>
                                                        <td><strong><?php echo htmlentities($row->FullName); ?></strong></td>
                                                        <td><?php echo htmlentities($row->MobileNumber); ?></td>
                                                        <td><?php echo htmlentities($row->name); ?></td>
                                                        <td><?php echo htmlentities($row->ContactNumber); ?></td>
                                                        <td><?php echo htmlentities($row->EmailId); ?></td>
                                                        <td><?php echo htmlentities($row->BloodRequirefor); ?></td>
                                                        <td><?php echo htmlentities($row->Message); ?></td>
                                                        <td><?php echo date('d M Y', strtotime($row->ApplyDate)); ?></td>
                                                    </tr>
                                                    <?php 
                                                        $cnt++;
                                                        }
                                                    } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="9" class="no-results">
                                                            <i class="fas fa-search fa-2x"></i>
                                                            <h4>No matching donors found</h4>
                                                            <p>Try searching with a different name or phone number</p>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <div class="dashboard-card mt-4">
                                    <div class="card-header">
                                        <h5 class="mb-0">Donor Management Tips</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="d-flex align-items-start mb-4">
                                                    <div class="p-3 rounded-circle me-3" style="background: rgba(67, 97, 238, 0.1); color: #4361ee;">
                                                        <i class="fas fa-search"></i>
                                                    </div>
                                                    <div>
                                                        <h6>Effective Searching</h6>
                                                        <p class="mb-0">Use partial names or phone numbers to find donors more easily.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="d-flex align-items-start mb-4">
                                                    <div class="p-3 rounded-circle me-3" style="background: rgba(76, 201, 240, 0.1); color: #4cc9f0;">
                                                        <i class="fas fa-history"></i>
                                                    </div>
                                                    <div>
                                                        <h6>Request History</h6>
                                                        <p class="mb-0">Review past requests to understand donor engagement patterns.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="d-flex align-items-start">
                                                    <div class="p-3 rounded-circle me-3" style="background: rgba(247, 37, 133, 0.1); color: #f72585;">
                                                        <i class="fas fa-user-clock"></i>
                                                    </div>
                                                    <div>
                                                        <h6>Donor Retention</h6>
                                                        <p class="mb-0">Regularly engage with donors to maintain active participation.</p>
                                                    </div>
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
        </div>
    </main>

    <!-- Loading Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
    
    <script>
        // Add animation to table rows
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.data-table tbody tr');
            rows.forEach((row, index) => {
                setTimeout(() => {
                    row.style.opacity = 0;
                    row.style.transform = 'translateY(10px)';
                    row.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    
                    setTimeout(() => {
                        row.style.opacity = 1;
                        row.style.transform = 'translateY(0)';
                    }, 10);
                }, index * 50);
            });
            
            // Focus on search field when page loads
            const searchField = document.getElementById('searchdata');
            if(searchField) {
                searchField.focus();
            }
        });
    </script>
</body>
</html>
<?php } ?>