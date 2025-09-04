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
    
    <title>Admin Dashboard | Life Savior</title>

    <!-- Favicon -->
    <link rel="icon" href="../admin/img/icon.jpg" type="image/jpeg">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <!-- Bootstrap Datatables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
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
        }
        
        .page-title {
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid rgba(67, 97, 238, 0.2);
        }
        
        /* Dashboard Cards */
        .dashboard-card {
            border-radius: 12px;
            border: none;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            margin-bottom: 1.5rem;
            height: 100%;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }
        
        .dashboard-card .card-header {
            border-bottom: none;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 1.25rem 1.5rem;
            border-radius: 12px 12px 0 0 !important;
        }
        
        .dashboard-card .card-body {
            padding: 1.75rem 1.5rem;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0.5rem 0;
            color: var(--dark);
        }
        
        .stat-title {
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #718096;
            font-weight: 600;
        }
        
        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.2;
            position: absolute;
            right: 1.5rem;
            top: 1.5rem;
        }
        
        .card-link {
            display: block;
            padding: 0.75rem 1.5rem;
            background-color: rgba(67, 97, 238, 0.05);
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border-top: 1px solid rgba(0,0,0,0.05);
        }
        
        .card-link:hover {
            background-color: var(--primary);
            color: white;
        }
        
        .card-link i {
            transition: transform 0.3s ease;
        }
        
        .card-link:hover i {
            transform: translateX(3px);
        }
        
        /* Summary Section */
        .summary-section {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .summary-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--primary);
        }
        
        /* Activity Section */
        .activity-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            padding: 1.5rem;
            height: 100%;
        }
        
        .activity-item {
            display: flex;
            align-items: flex-start;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        
        .bg-icon-primary {
            background-color: rgba(67, 97, 238, 0.15);
            color: var(--primary);
        }
        
        .bg-icon-success {
            background-color: rgba(76, 201, 240, 0.15);
            color: #4cc9f0;
        }
        
        .bg-icon-warning {
            background-color: rgba(247, 37, 133, 0.15);
            color: #f72585;
        }
        
        .activity-content {
            flex: 1;
        }
        
        .activity-time {
            font-size: 0.85rem;
            color: #a0aec0;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .stat-number {
                font-size: 2rem;
            }
            
            .dashboard-card .card-body {
                padding: 1.25rem;
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
                <div class="row mb-4">
                    <div class="col">
                        <h2 class="page-title">Dashboard Overview</h2>
                        <p class="text-muted">Welcome back! Here's what's happening with your system today.</p>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h5 class="mb-0">Blood Groups</h5>
                            </div>
                            <div class="card-body position-relative">
                                <i class="fas fa-tint stat-icon"></i>
                                <?php 
                                $sql ="SELECT id from tblbloodgroup ";
                                $query = $dbh -> prepare($sql);
                                $query->execute();
                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                $bg=$query->rowCount();
                                ?>
                                <div class="stat-number"><?php echo htmlentities($bg);?></div>
                                <div class="stat-title">Listed Blood Groups</div>
                            </div>
                            <a href="manage-bloodgroup.php" class="card-link">View Details <i class="fas fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6">
                        <div class="dashboard-card">
                            <div class="card-header" style="background: linear-gradient(135deg, #4cc9f0, #4895ef);">
                                <h5 class="mb-0">Blood Donors</h5>
                            </div>
                            <div class="card-body position-relative">
                                <i class="fas fa-user-friends stat-icon"></i>
                                <?php 
                                $sql1 ="SELECT id from tblblooddonars ";
                                $query1 = $dbh -> prepare($sql1);;
                                $query1->execute();
                                $results1=$query1->fetchAll(PDO::FETCH_OBJ);
                                $regbd=$query1->rowCount();
                                ?>
                                <div class="stat-number"><?php echo htmlentities($regbd);?></div>
                                <div class="stat-title">Registered Donors</div>
                            </div>
                            <a href="donor-list.php" class="card-link">View Details <i class="fas fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6">
                        <div class="dashboard-card">
                            <div class="card-header" style="background: linear-gradient(135deg, #7209b7, #560bad);">
                                <h5 class="mb-0">Support Queries</h5>
                            </div>
                            <div class="card-body position-relative">
                                <i class="fas fa-question-circle stat-icon"></i>
                                <?php 
                                $sql6 ="SELECT id from tblcontactusquery ";
                                $query6 = $dbh -> prepare($sql6);;
                                $query6->execute();
                                $results6=$query6->fetchAll(PDO::FETCH_OBJ);
                                $query=$query6->rowCount();
                                ?>
                                <div class="stat-number"><?php echo htmlentities($query);?></div>
                                <div class="stat-title">Total Queries</div>
                            </div>
                            <a href="manage-conactusquery.php" class="card-link">View Details <i class="fas fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6">
                        <div class="dashboard-card">
                            <div class="card-header" style="background: linear-gradient(135deg, #f72585, #b5179e);">
                                <h5 class="mb-0">Blood Requests</h5>
                            </div>
                            <div class="card-body position-relative">
                                <i class="fas fa-heartbeat stat-icon"></i>
                                <?php 
                                $sql6 ="SELECT ID  from tblbloodrequirer ";
                                $query6 = $dbh -> prepare($sql6);;
                                $query6->execute();
                                $results6=$query6->fetchAll(PDO::FETCH_OBJ);
                                $totalreuqests=$query6->rowCount();
                                ?>
                                <div class="stat-number"><?php echo htmlentities($totalreuqests);?></div>
                                <div class="stat-title">Requests Received</div>
                            </div>
                            <a href="requests-received.php" class="card-link">View Details <i class="fas fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Summary & Activity Section -->
                <div class="row mt-4">
                    <div class="col-lg-8">
                        <div class="summary-section">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="summary-title">Recent Activity</h5>
                                <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                            </div>
                            
                            <div class="activity-list">
                                <div class="activity-item">
                                    <div class="activity-icon bg-icon-primary">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                    <div class="activity-content">
                                        <h6 class="mb-1">New donor registered</h6>
                                        <p class="mb-0">John Doe registered as a blood donor with type O+</p>
                                        <div class="activity-time">10 minutes ago</div>
                                    </div>
                                </div>
                                
                                <div class="activity-item">
                                    <div class="activity-icon bg-icon-success">
                                        <i class="fas fa-tint"></i>
                                    </div>
                                    <div class="activity-content">
                                        <h6 class="mb-1">Blood request fulfilled</h6>
                                        <p class="mb-0">Request for 2 units of A+ blood has been fulfilled</p>
                                        <div class="activity-time">2 hours ago</div>
                                    </div>
                                </div>
                                
                                <div class="activity-item">
                                    <div class="activity-icon bg-icon-warning">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </div>
                                    <div class="activity-content">
                                        <h6 class="mb-1">Low stock alert</h6>
                                        <p class="mb-0">Blood type AB- is running low (only 3 units left)</p>
                                        <div class="activity-time">5 hours ago</div>
                                    </div>
                                </div>
                                
                                <div class="activity-item">
                                    <div class="activity-icon bg-icon-primary">
                                        <i class="fas fa-question-circle"></i>
                                    </div>
                                    <div class="activity-content">
                                        <h6 class="mb-1">New support query</h6>
                                        <p class="mb-0">Sarah Johnson submitted a question about donation eligibility</p>
                                        <div class="activity-time">Yesterday</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="activity-card">
                            <h5 class="summary-title mb-4">Quick Stats</h5>
                            
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-icon-primary p-3 rounded-circle me-3">
                                    <i class="fas fa-tint fs-4"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Most Available</div>
                                    <div class="h5 mb-0">O+ (42 units)</div>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-icon-warning p-3 rounded-circle me-3">
                                    <i class="fas fa-exclamation-triangle fs-4"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Lowest Stock</div>
                                    <div class="h5 mb-0">AB- (3 units)</div>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-icon-success p-3 rounded-circle me-3">
                                    <i class="fas fa-user-clock fs-4"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Active Donors</div>
                                    <div class="h5 mb-0">87%</div>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center">
                                <div class="bg-icon-primary p-3 rounded-circle me-3">
                                    <i class="fas fa-bolt fs-4"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Avg. Response Time</div>
                                    <div class="h5 mb-0">2.4 hours</div>
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
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="js/main.js"></script>
    
    <script>
        // Simple animation for stats
        $(document).ready(function() {
            $('.stat-number').each(function() {
                $(this).prop('Counter', 0).animate({
                    Counter: $(this).text()
                }, {
                    duration: 2000,
                    easing: 'swing',
                    step: function(now) {
                        $(this).text(Math.ceil(now));
                    }
                });
            });
        });
    </script>
</body>
</html>
<?php } ?>