<?php
require_once 'config.php';
error_reporting(0);

// Get blood inventory summary
$inventory_summary = [];
$total_units = 0;
$blood_groups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

foreach ($blood_groups as $group) {
    $stmt = $conn->prepare("SELECT SUM(quantity) as total, status FROM blood_inventory WHERE blood_group = ? GROUP BY status");
    $stmt->bind_param("s", $group);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $group_data = ['total' => 0, 'status' => 'Available'];
    while ($row = $result->fetch_assoc()) {
        $group_data['total'] += $row['total'];
        if ($row['status'] == 'Critical') {
            $group_data['status'] = 'Critical';
        } elseif ($row['status'] == 'Low' && $group_data['status'] != 'Critical') {
            $group_data['status'] = 'Low';
        }
    }
    
    $inventory_summary[$group] = $group_data;
    $total_units += $group_data['total'];
}

// Get critical levels count
$critical_stmt = $conn->prepare("SELECT COUNT(DISTINCT blood_group) as critical_count FROM blood_inventory WHERE status = 'Critical'");
$critical_stmt->execute();
$critical_result = $critical_stmt->get_result();
$critical_count = $critical_result->fetch_assoc()['critical_count'];

// Get expiring soon count (within 7 days)
$expiring_stmt = $conn->prepare("SELECT SUM(quantity) as expiring_count FROM blood_inventory WHERE expiration_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)");
$expiring_stmt->execute();
$expiring_result = $expiring_stmt->get_result();
$expiring_count = $expiring_result->fetch_assoc()['expiring_count'];

// Get available donors count with error handling
$donors_count = 0;
try {
    $check_table = $conn->query("SHOW TABLES LIKE 'donors'");
    if ($check_table->num_rows > 0) {
        $donors_stmt = $conn->prepare("SELECT COUNT(*) as donors_count FROM donors WHERE (last_donation_date <= DATE_SUB(CURDATE(), INTERVAL 3 MONTH) OR last_donation_date IS NULL) AND status = 'Active'");
        $donors_stmt->execute();
        $donors_result = $donors_stmt->get_result();
        $donors_count = $donors_result->fetch_assoc()['donors_count'];
    }
} catch (Exception $e) {
    error_log("Donors count error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Inventory Management | Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #d32f2f;
            --primary-dark: #b71c1c;
            --secondary: #2c3e50;
            --light: #f5f7fa;
            --dark: #333;
            --success: #4caf50;
            --warning: #ff9800;
            --danger: #f44336;
            --gray: #e0e0e0;
            --border: #ddd;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f0f2f5;
            color: var(--dark);
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .admin-sidebar {
            width: 250px;
            background: var(--secondary);
            color: #ecf0f1;
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            transition: all 0.3s;
            z-index: 900;
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 20px 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: var(--primary);
            display: flex;
            align-items: center;
        }
        
        .sidebar-header h3 {
            font-weight: 500;
            margin-left: 10px;
        }
        
        .sidebar-header i {
            font-size: 24px;
        }
        
        .sidebar-list {
            list-style: none;
            padding: 15px 0;
        }
        
        .sidebar-item {
            position: relative;
        }
        
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #ecf0f1;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }
        
        .sidebar-link i {
            font-size: 16px;
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }
        
        .sidebar-link.active {
            background: rgba(255, 255, 255, 0.15);
            border-left: 4px solid var(--primary);
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
        }
        
        /* Header */
        .admin-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 0 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
        }
        
        .user-nav {
            position: relative;
        }
        
        .user-avatar {
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            cursor: pointer;
        }
        
        .avatar-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.3);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: var(--primary);
        }
        
        /* Page Content */
        .page-title {
            margin: 20px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .page-title h1 {
            font-weight: 600;
            color: var(--secondary);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .page-title h1 i {
            color: var(--primary);
        }
        
        /* Dashboard Cards */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 20px;
            transition: transform 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
        }
        
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        
        .card-title {
            font-size: 16px;
            color: #666;
            font-weight: 500;
        }
        
        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .card-icon.blood {
            background: rgba(211, 47, 47, 0.15);
            color: var(--primary);
        }
        
        .card-icon.donor {
            background: rgba(76, 175, 80, 0.15);
            color: var(--success);
        }
        
        .card-icon.critical {
            background: rgba(255, 152, 0, 0.15);
            color: var(--warning);
        }
        
        .card-icon.expiring {
            background: rgba(244, 67, 54, 0.15);
            color: var(--danger);
        }
        
        .card-value {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .card-footer {
            color: #777;
            font-size: 14px;
            display: flex;
            align-items: center;
        }
        
        .card-footer i {
            margin-right: 5px;
        }
        
        /* Charts Container */
        .charts-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        @media (max-width: 992px) {
            .charts-container {
                grid-template-columns: 1fr;
            }
        }
        
        .chart-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 20px;
        }
        
        .chart-header {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .chart-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--secondary);
        }
        
        .chart-actions {
            display: flex;
            gap: 10px;
        }
        
        .chart-actions button {
            background: none;
            border: 1px solid var(--border);
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
        }
        
        .chart-actions button:hover {
            background: #f5f5f5;
        }
        
        .chart-actions button.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .chart-container {
            height: 300px;
            position: relative;
        }
        
        /* Inventory Table */
        .inventory-table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .table-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .table-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--secondary);
        }
        
        .table-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 8px 16px;
            border-radius: 5px;
            border: none;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
        }
        
        .btn-outline {
            background: none;
            border: 1px solid var(--primary);
            color: var(--primary);
        }
        
        .btn-outline:hover {
            background: rgba(211, 47, 47, 0.1);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }
        
        th {
            background: #f8f9fa;
            font-weight: 600;
            color: var(--secondary);
        }
        
        tr:hover {
            background: #f8f9fa;
        }
        
        .blood-group {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 12px;
            background: rgba(211, 47, 47, 0.1);
            color: var(--primary);
        }
        
        .status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status.available {
            background: rgba(76, 175, 80, 0.15);
            color: var(--success);
        }
        
        .status.low {
            background: rgba(255, 152, 0, 0.15);
            color: var(--warning);
        }
        
        .status.critical {
            background: rgba(244, 67, 54, 0.15);
            color: var(--danger);
        }
        
        .action-btn {
            background: none;
            border: none;
            padding: 5px;
            cursor: pointer;
            color: #666;
            transition: all 0.2s;
        }
        
        .action-btn:hover {
            color: var(--primary);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                width: 70px;
            }
            
            .admin-sidebar .sidebar-title,
            .admin-sidebar .sidebar-link span,
            .admin-sidebar .dropdown-arrow {
                display: none;
            }
            
            .admin-sidebar .sidebar-link {
                justify-content: center;
                padding: 15px;
            }
            
            .admin-sidebar .sidebar-link i {
                margin-right: 0;
                font-size: 20px;
            }
            
            .main-content {
                margin-left: 70px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="admin-sidebar">
        <div class="sidebar-header">
            <i class="fas fa-tint"></i>
            <h3 class="sidebar-title">BloodBank</h3>
        </div>
        <ul class="sidebar-list">
            <li class="sidebar-item">
                <a href="dashboard.php" class="sidebar-link active">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="sidebar-item">
                <a href="blood-inventory.php" class="sidebar-link">
                    <i class="fas fa-tint"></i>
                    <span>Blood Inventory</span>
                </a>
            </li>
            
            <li class="sidebar-item">
                <a href="donor-list.php" class="sidebar-link">
                    <i class="fas fa-users"></i>
                    <span>Donor List</span>
                </a>
            </li>
            
            <li class="sidebar-item">
                <a href="manage-contact-queries.php" class="sidebar-link">
                    <i class="fas fa-envelope"></i>
                    <span>Contact Queries</span>
                </a>
            </li>
            
            <li class="sidebar-item">
                <a href="manage-pages.php" class="sidebar-link">
                    <i class="fas fa-file-alt"></i>
                    <span>Manage Pages</span>
                </a>
            </li>
            
            <li class="sidebar-item">
                <a href="update-contact-info.php" class="sidebar-link">
                    <i class="fas fa-address-card"></i>
                    <span>Contact Info</span>
                </a>
            </li>
            
            <li class="sidebar-item">
                <a href="donation-requests.php" class="sidebar-link">
                    <i class="fas fa-hand-holding-heart"></i>
                    <span>Donation Requests</span>
                </a>
            </li>
            
            <li class="sidebar-item">
                <a href="logout.php" class="sidebar-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header class="admin-header">
            <div class="header-content">
                <div></div>
                <div class="user-nav">
                    <div class="user-avatar">
                        <div class="avatar-img"><?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?></div>
                        <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Title -->
        <div class="page-title">
            <h1><i class="fas fa-tachometer-alt"></i> Dashboard Overview</h1>
            <div class="table-actions">
                <button class="btn btn-outline"><i class="fas fa-filter"></i> Filter</button>
                <button class="btn btn-primary" onclick="window.location.href='blood-inventory.php'">
                    <i class="fas fa-tint"></i> View Inventory
                </button>
            </div>
        </div>

        <!-- Dashboard Cards -->
        <div class="dashboard-cards">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Total Blood Units</div>
                    <div class="card-icon blood">
                        <i class="fas fa-tint"></i>
                    </div>
                </div>
                <div class="card-value"><?php echo number_format($total_units); ?></div>
                <div class="card-footer">
                    <i class="fas fa-arrow-up"></i> Updated in real-time
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Available Donors</div>
                    <div class="card-icon donor">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
                <div class="card-value"><?php echo number_format($donors_count); ?></div>
                <div class="card-footer">
                    <i class="fas fa-info-circle"></i> Eligible to donate
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Critical Levels</div>
                    <div class="card-icon critical">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
                <div class="card-value"><?php echo $critical_count; ?> Groups</div>
                <div class="card-footer">
                    <i class="fas fa-tint"></i> Need immediate attention
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Expiring Soon</div>
                    <div class="card-icon expiring">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="card-value"><?php echo number_format($expiring_count); ?> Units</div>
                <div class="card-footer">
                    <i class="fas fa-calendar"></i> Within next 7 days
                </div>
            </div>
        </div>

        <!-- Charts Container -->
        <div class="charts-container">
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title">Blood Group Distribution</div>
                    <div class="chart-actions">
                        <button id="week-btn">Week</button>
                        <button id="month-btn" class="active">Month</button>
                        <button id="quarter-btn">Quarter</button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="bloodDistributionChart"></canvas>
                </div>
            </div>
            
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title">Inventory Status</div>
                    <div class="chart-actions">
                        <button id="download-pie"><i class="fas fa-download"></i></button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="inventoryStatusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="inventory-table-container">
            <div class="table-header">
                <div class="table-title">Recent Inventory Activity</div>
                <div class="table-actions">
                    <button class="btn btn-outline" id="refresh-activity"><i class="fas fa-sync-alt"></i> Refresh</button>
                </div>
            </div>
            
            <table id="activity-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Action</th>
                        <th>Blood Group</th>
                        <th>Quantity</th>
                        <th>Changed By</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $activity_stmt = $conn->prepare("
                        SELECT h.action_date, h.action, i.blood_group, h.old_quantity, h.new_quantity, a.username, h.notes 
                        FROM inventory_history h
                        JOIN blood_inventory i ON h.inventory_id = i.inventory_id
                        JOIN admins a ON h.changed_by = a.admin_id
                        ORDER BY h.action_date DESC
                        LIMIT 10
                    ");
                    $activity_stmt->execute();
                    $activity_result = $activity_stmt->get_result();
                    
                    if ($activity_result->num_rows > 0) {
                        while ($row = $activity_result->fetch_assoc()) {
                            $change = $row['new_quantity'] - $row['old_quantity'];
                            $change_text = ($change > 0) ? "+$change" : $change;
                            
                            echo "<tr>
                                <td>" . date('Y-m-d H:i', strtotime($row['action_date'])) . "</td>
                                <td>{$row['action']}</td>
                                <td><span class=\"blood-group\">{$row['blood_group']}</span></td>
                                <td>{$change_text}</td>
                                <td>{$row['username']}</td>
                                <td>" . htmlspecialchars($row['notes']) . "</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan=\"6\">No recent activity found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Initialize charts with dynamic data
        document.addEventListener('DOMContentLoaded', function() {
            // Blood Group Distribution Chart (Bar Chart)
            const distCtx = document.getElementById('bloodDistributionChart').getContext('2d');
            const distChart = new Chart(distCtx, {
                type: 'bar',
                data: {
                    labels: ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
                    datasets: [{
                        label: 'Blood Units',
                        data: [
                            <?php echo (int)$inventory_summary['A+']['total']; ?>,
                            <?php echo (int)$inventory_summary['A-']['total']; ?>,
                            <?php echo (int)$inventory_summary['B+']['total']; ?>,
                            <?php echo (int)$inventory_summary['B-']['total']; ?>,
                            <?php echo (int)$inventory_summary['AB+']['total']; ?>,
                            <?php echo (int)$inventory_summary['AB-']['total']; ?>,
                            <?php echo (int)$inventory_summary['O+']['total']; ?>,
                            <?php echo (int)$inventory_summary['O-']['total']; ?>
                        ],
                        backgroundColor: [
                            '#e53935', '#ef5350', '#d32f2f', '#c62828',
                            '#f44336', '#e57373', '#b71c1c', '#d81b60'
                        ],
                        borderWidth: 0,
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
            
            // Inventory Status Chart (Pie Chart)
            const statusCtx = document.getElementById('inventoryStatusChart').getContext('2d');
            const statusChart = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Available', 'Low', 'Critical'],
                    datasets: [{
                        data: [
                            <?php 
                                $available = 0;
                                $low = 0;
                                $critical = 0;
                                foreach ($inventory_summary as $group) {
                                    if ($group['status'] == 'Available') $available++;
                                    elseif ($group['status'] == 'Low') $low++;
                                    elseif ($group['status'] == 'Critical') $critical++;
                                }
                                echo "$available, $low, $critical";
                            ?>
                        ],
                        backgroundColor: [
                            '#4caf50', '#ff9800', '#f44336'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    },
                    cutout: '70%'
                }
            });
            
            // Chart period buttons
            document.querySelectorAll('.chart-actions button').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (this.id === 'download-pie') {
                        // Download chart as image
                        const link = document.createElement('a');
                        link.download = 'inventory-status.png';
                        link.href = document.getElementById('inventoryStatusChart').toDataURL('image/png');
                        link.click();
                    } else {
                        this.parentElement.querySelectorAll('button').forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                        
                        // Update the chart title based on selected period
                        let period = 'Month';
                        if (this.id === 'week-btn') period = 'Week';
                        else if (this.id === 'quarter-btn') period = 'Quarter';
                        
                        document.querySelector('.chart-title').textContent = `Blood Group Distribution (Last ${period})`;
                    }
                });
            });
            
            // Refresh activity button
            document.getElementById('refresh-activity').addEventListener('click', function() {
                location.reload();
            });
        });
    </script>
</body>
</html>