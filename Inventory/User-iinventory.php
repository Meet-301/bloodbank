<?php
require_once 'config.php';

// Check session status instead of starting new session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
// if (!isset($_SESSION['user_id'])) {
//     header("Location: ../login.php");
//     exit();
// }

// Get all blood inventory data (modified query without is_deleted check)
$inventory = [];
try {
    $stmt = $conn->prepare("SELECT blood_group, SUM(quantity) as total_quantity, 
                           COUNT(*) as donation_count 
                           FROM blood_inventory 
                           GROUP BY blood_group");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $inventory[$row['blood_group']] = $row;
    }
    $stmt->close();
} catch (mysqli_sql_exception $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Inventory | User Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #d32f2f;
            --primary-light: #ff6659;
            --primary-dark: #9a0007;
            --secondary: #263238;
            --secondary-light: #4f5b62;
            --secondary-dark: #000a12;
            --success: #388e3c;
            --info: #1976d2;
            --warning: #ffa000;
            --danger: #d32f2f;
            --light: #f5f5f5;
            --dark: #212121;
            --white: #ffffff;
            --gray: #e0e0e0;
            --border: #e0e0e0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: var(--secondary);
            line-height: 1.6;
        }

        /* Header Styles */
        .user-header {
            background: white;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-left h2 {
            color: var(--secondary);
            font-weight: 600;
        }

        .header-right {
            display: flex;
            align-items: center;
        }

        .user-profile {
            display: flex;
            align-items: center;
            cursor: pointer;
            position: relative;
        }

        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
        }

        /* Page Title Styles */
        .page-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 25px;
            background: white;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .page-title h1 {
            color: var(--secondary);
            font-size: 24px;
            font-weight: 600;
        }

        .page-title h1 i {
            margin-right: 10px;
            color: var(--primary);
        }

        /* Main Content Styles */
        .main-content {
            padding: 20px;
        }

        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .stat-card .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
        }

        .stat-card .bg-a { background: #d32f2f; }
        .stat-card .bg-b { background: #1976d2; }
        .stat-card .bg-ab { background: #388e3c; }
        .stat-card .bg-o { background: #ffa000; }

        .stat-card .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--secondary);
        }

        .stat-card .stat-label {
            font-size: 14px;
            color: var(--secondary-light);
            margin-top: 5px;
        }

        /* Inventory Table */
        .inventory-table {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        th {
            background: var(--light);
            font-weight: 600;
            color: var(--secondary);
        }

        tr:hover {
            background: rgba(0, 0, 0, 0.02);
        }

        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-available { background: rgba(56, 142, 60, 0.15); color: var(--success); }
        .status-low { background: rgba(255, 160, 0, 0.15); color: var(--warning); }
        .status-critical { background: rgba(211, 47, 47, 0.15); color: var(--danger); }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .page-title {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="user-header">
        <div class="header-left">
            <h2>Blood Bank Management System</h2>
        </div>
        <div class="header-right">
            <div class="user-profile">
                <img src="../assets/user-avatar.jpg" alt="User Profile">
                <div class="user-info">
                    <div class="user-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
                    <div class="user-role">Donor</div>
                </div>
            </div>
        </div>
    </header>

    <!-- Page Title -->
    <div class="page-title">
        <h1><i class="fas fa-tint"></i> Blood Inventory Status</h1>
        <div>
            <span class="last-updated">Last updated: <?php echo date('F j, Y, g:i a'); ?></span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-value"><?php echo $inventory['A+']['total_quantity'] ?? 0; ?></div>
                        <div class="stat-label">Units Available</div>
                    </div>
                    <div class="stat-icon bg-a">A+</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-value"><?php echo $inventory['B+']['total_quantity'] ?? 0; ?></div>
                        <div class="stat-label">Units Available</div>
                    </div>
                    <div class="stat-icon bg-b">B+</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-value"><?php echo $inventory['AB+']['total_quantity'] ?? 0; ?></div>
                        <div class="stat-label">Units Available</div>
                    </div>
                    <div class="stat-icon bg-ab">AB+</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-value"><?php echo $inventory['O+']['total_quantity'] ?? 0; ?></div>
                        <div class="stat-label">Units Available</div>
                    </div>
                    <div class="stat-icon bg-o">O+</div>
                </div>
            </div>
        </div>

        <!-- Detailed Inventory Table -->
        <div class="inventory-table">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Blood Group</th>
                            <th>Available Units</th>
                            <th>Donations Count</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $group): ?>
                            <?php 
                            $quantity = $inventory[$group]['total_quantity'] ?? 0;
                            $status = 'Available';
                            if ($quantity < 5) $status = 'Low';
                            if ($quantity < 2) $status = 'Critical';
                            ?>
                            <tr>
                                <td><?php echo $group; ?></td>
                                <td><?php echo $quantity; ?></td>
                                <td><?php echo $inventory[$group]['donation_count'] ?? 0; ?></td>
                                <td><span class="status status-<?php echo strtolower($status); ?>"><?php echo $status; ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Auto-refresh script -->
    <script>
        // Auto-refresh every 5 minutes (300000 ms)
        setTimeout(function(){
            window.location.reload();
        }, 300000);
        
        // You could also use AJAX for a more seamless experience
        /*
        function refreshInventory() {
            fetch('get-inventory-data.php')
                .then(response => response.json())
                .then(data => {
                    // Update the DOM with new data
                    document.querySelector('.last-updated').textContent = 'Last updated: ' + new Date().toLocaleString();
                    // Update other elements as needed
                });
        }
        setInterval(refreshInventory, 300000);
        */
    </script>
</body>
</html>