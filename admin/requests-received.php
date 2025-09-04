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
    
    <title>BBDMS | Blood Requests Received</title>

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
        
        .request-status {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .status-pending {
            background-color: rgba(247, 37, 133, 0.1);
            color: #f72585;
        }
        
        .status-completed {
            background-color: rgba(76, 201, 240, 0.1);
            color: #4cc9f0;
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
        
        .no-requests {
            text-align: center;
            padding: 2rem;
            color: #718096;
        }
        
        .no-requests i {
            font-size: 3rem;
            color: #cbd5e0;
            margin-bottom: 1rem;
        }
        
        .message-preview {
            max-width: 250px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .request-date {
            min-width: 100px;
        }
        
        .request-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(67, 97, 238, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            color: var(--primary);
        }
        
        .request-summary-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }
        
        .request-summary-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }
        
        .request-summary-content {
            flex: 1;
        }
        
        .request-summary-title {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.25rem;
        }
        
        .request-summary-meta {
            color: #718096;
            font-size: 0.9rem;
        }
        
        .request-summary-badge {
            background: rgba(76, 201, 240, 0.1);
            color: #4cc9f0;
            border-radius: 20px;
            padding: 0.25rem 0.75rem;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        @media (max-width: 992px) {
            .data-table thead {
                display: none;
            }
            
            .data-table tr {
                display: block;
                margin-bottom: 1.5rem;
                border-radius: 12px;
                box-shadow: var(--card-shadow);
                overflow: hidden;
            }
            
            .data-table td {
                display: block;
                text-align: right;
                border-bottom: 1px solid #e2e8f0;
            }
            
            .data-table td:before {
                content: attr(data-label);
                float: left;
                font-weight: 600;
                color: var(--primary);
            }
            
            .data-table td:last-child {
                border-bottom: none;
            }
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
                    <a class="nav-link active" href="requests-received.php">
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
                <h3 class="mb-0">Blood Request Management</h3>
                <p class="text-muted mb-0">View and manage blood requests</p>
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
                        <h2 class="page-title">Blood Requests Received</h2>
                        <p class="text-muted">View and manage all blood requests received by donors</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h5 class="mb-0">All Blood Requests</h5>
                            </div>
                            <div class="card-body">
                                <?php
                                // Fetch all blood requests
                                $sql = "SELECT tblbloodrequirer.BloodDonarID, tblbloodrequirer.name, tblbloodrequirer.EmailId, tblbloodrequirer.ContactNumber, tblbloodrequirer.BloodRequirefor, tblbloodrequirer.Message, tblbloodrequirer.ApplyDate, tblblooddonars.id as donid, tblblooddonars.FullName, tblblooddonars.MobileNumber 
                                        FROM tblbloodrequirer 
                                        JOIN tblblooddonars ON tblblooddonars.id = tblbloodrequirer.BloodDonarID";
                                $query = $dbh->prepare($sql);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                $cnt = 1;
                                ?>
                                
                                <?php if($query->rowCount() > 0): ?>
                                <!-- Summary Cards for Mobile View -->
                                <div class="d-md-none">
                                    <?php foreach($results as $row): ?>
                                    <div class="request-summary-card">
                                        <div class="request-icon">
                                            <i class="fas fa-heartbeat"></i>
                                        </div>
                                        <div class="request-summary-content">
                                            <div class="request-summary-title">
                                                <?php echo htmlentities($row->name); ?> 
                                                <span class="request-summary-badge"><?php echo htmlentities($row->BloodRequirefor); ?></span>
                                            </div>
                                            <div class="request-summary-meta">
                                                <div><i class="fas fa-user me-1"></i> <?php echo htmlentities($row->FullName); ?></div>
                                                <div><i class="fas fa-phone me-1"></i> <?php echo htmlentities($row->ContactNumber); ?></div>
                                                <div class="mt-1"><?php echo date('d M Y', strtotime($row->ApplyDate)); ?></div>
                                            </div>
                                        </div>
                                        <div>
                                            <i class="fas fa-chevron-right text-muted"></i>
                                        </div>
                                    </div>
                                    <?php $cnt++; endforeach; ?>
                                </div>
                                
                                <!-- Table for Desktop View -->
                                <div class="d-none d-md-block">
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
                                                <?php foreach($results as $row): ?>
                                                <tr>
                                                    <td><?php echo htmlentities($cnt); ?></td>
                                                    <td><strong><?php echo htmlentities($row->FullName); ?></strong></td>
                                                    <td><?php echo htmlentities($row->MobileNumber); ?></td>
                                                    <td><?php echo htmlentities($row->name); ?></td>
                                                    <td><?php echo htmlentities($row->ContactNumber); ?></td>
                                                    <td><?php echo htmlentities($row->EmailId); ?></td>
                                                    <td>
                                                        <span class="request-status status-pending">
                                                            <?php echo htmlentities($row->BloodRequirefor); ?>
                                                        </span>
                                                    </td>
                                                    <td class="message-preview" title="<?php echo htmlentities($row->Message); ?>">
                                                        <?php echo htmlentities($row->Message); ?>
                                                    </td>
                                                    <td class="request-date">
                                                        <?php echo date('d M Y', strtotime($row->ApplyDate)); ?>
                                                    </td>
                                                </tr>
                                                <?php $cnt++; endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php else: ?>
                                <div class="no-requests">
                                    <i class="fas fa-inbox fa-3x"></i>
                                    <h4>No Blood Requests Found</h4>
                                    <p>There are currently no blood requests in the system</p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="dashboard-card mt-4">
                            <div class="card-header" style="background: linear-gradient(135deg, #f72585, #b5179e);">
                                <h5 class="mb-0">Request Management Insights</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-start mb-4">
                                            <div class="p-3 rounded-circle me-3" style="background: rgba(247, 37, 133, 0.1); color: #f72585;">
                                                <i class="fas fa-bolt"></i>
                                            </div>
                                            <div>
                                                <h6>Quick Response</h6>
                                                <p class="mb-0">Respond to blood requests within 24 hours for best outcomes.</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-start mb-4">
                                            <div class="p-3 rounded-circle me-3" style="background: rgba(76, 201, 240, 0.1); color: #4cc9f0;">
                                                <i class="fas fa-chart-line"></i>
                                            </div>
                                            <div>
                                                <h6>Track Trends</h6>
                                                <p class="mb-0">Monitor request patterns to anticipate future blood needs.</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-start">
                                            <div class="p-3 rounded-circle me-3" style="background: rgba(67, 97, 238, 0.1); color: #4361ee;">
                                                <i class="fas fa-users"></i>
                                            </div>
                                            <div>
                                                <h6>Donor Engagement</h6>
                                                <p class="mb-0">Regularly update donors about how their donations help save lives.</p>
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
        // Add responsive table functionality
        document.addEventListener('DOMContentLoaded', function() {
            // For mobile view, add data labels
            if(window.innerWidth < 992) {
                const headers = document.querySelectorAll('.data-table thead th');
                const rows = document.querySelectorAll('.data-table tbody tr');
                
                headers.forEach((header, index) => {
                    if(index > 0) { // Skip the first header (#)
                        const headerText = header.textContent;
                        rows.forEach(row => {
                            const cell = row.querySelector(`td:nth-child(${index + 1})`);
                            if(cell) {
                                cell.setAttribute('data-label', headerText);
                            }
                        });
                    }
                });
            }
            
            // Add animation to table rows
            const rows = document.querySelectorAll('.data-table tbody tr, .request-summary-card');
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
        });
    </script>
</body>
</html>
<?php } ?>