<?php
require_once 'config.php';


// Get all blood inventory
$inventory_stmt = $conn->prepare("SELECT * FROM blood_inventory ORDER BY blood_group, status");
$inventory_stmt->execute();
$inventory_result = $inventory_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Inventory Management | Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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
    --sidebar-width: 250px;
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

/* Sidebar Styles */
.admin-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: var(--sidebar-width);
    height: 100%;
    background: var(--secondary);
    color: white;
    padding: 20px 0;
    transition: all 0.3s;
    z-index: 1000;
}

.sidebar-header {
    padding: 0 20px 20px;
    text-align: center;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.sidebar-header h3 {
    color: white;
    margin-bottom: 5px;
}

.sidebar-header p {
    color: var(--gray);
    font-size: 12px;
}

.sidebar-menu {
    padding: 20px 0;
}

.sidebar-menu ul {
    list-style: none;
}

.sidebar-menu li {
    position: relative;
}

.sidebar-menu li a {
    display: block;
    padding: 12px 20px;
    color: var(--gray);
    text-decoration: none;
    transition: all 0.3s;
}

.sidebar-menu li a:hover,
.sidebar-menu li a.active {
    color: white;
    background: rgba(255, 255, 255, 0.1);
}

.sidebar-menu li a i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
}

/* Main Content Styles */
.main-content {
    margin-left: var(--sidebar-width);
    min-height: 100vh;
    transition: all 0.3s;
}

/* Header Styles */
.admin-header {
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

.user-profile .user-info {
    line-height: 1.3;
}

.user-profile .user-info .user-name {
    font-weight: 600;
    color: var(--secondary);
}

.user-profile .user-info .user-role {
    font-size: 12px;
    color: var(--secondary-light);
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

/* Button Styles */
.btn {
    display: inline-block;
    padding: 10px 20px;
    border-radius: 5px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
    border: none;
    font-size: 14px;
}

.btn-primary {
    background: var(--primary);
    color: white;
}

.btn-primary:hover {
    background: var(--primary-dark);
}

.btn-outline {
    background: transparent;
    color: var(--primary);
    border: 1px solid var(--primary);
}

.btn-outline:hover {
    background: var(--primary);
    color: white;
}

.btn-sm {
    padding: 5px 10px;
    font-size: 12px;
}

/* Form Container Styles */
.form-container {
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    padding: 30px;
    max-width: 800px;
    margin: 0 auto;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: var(--secondary);
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid var(--border);
    border-radius: 5px;
    font-size: 16px;
    transition: border 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary);
}

.form-row {
    display: flex;
    gap: 20px;
}

.form-row .form-group {
    flex: 1;
}

/* Alert Styles */
.alert {
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.alert-success {
    background: rgba(76, 175, 80, 0.15);
    color: var(--success);
    border: 1px solid var(--success);
}

.alert-danger {
    background: rgba(244, 67, 54, 0.15);
    color: var(--danger);
    border: 1px solid var(--danger);
}

/* Button Container Styles */
.btn-container {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .admin-sidebar {
        width: 0;
        overflow: hidden;
    }
    
    .main-content {
        margin-left: 0;
    }
    
    .form-row {
        flex-direction: column;
        gap: 0;
    }
}
    :root {
        --primary: #d32f2f;
        --primary-dark: #b71c1c;
        --primary-light: #ff6659;
        --secondary: #2e7d32;
        --secondary-dark: #005005;
        --secondary-light: #60ad5e;
        --warning: #ff8f00;
        --warning-dark: #c56000;
        --warning-light: #ffc046;
        --light: #f5f7fa;
        --dark: #333;
        --gray: #757575;
        --light-gray: #e0e0e0;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        background-color: #f5f7fa;
        display: flex;
        min-height: 100vh;
    }

    /* Sidebar Styles */
    .admin-sidebar {
        width: 250px;
        background: white;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px 0;
        height: 100vh;
        position: fixed;
    }

    .sidebar-header {
        text-align: center;
        padding: 0 20px 20px;
        border-bottom: 1px solid var(--light-gray);
    }

    .sidebar-header h2 {
        color: var(--primary);
        margin-top: 10px;
    }

    .sidebar-header i {
        font-size: 40px;
        color: var(--primary);
    }

    .sidebar-menu {
        margin-top: 20px;
    }

    .menu-item {
        padding: 12px 20px;
        display: flex;
        align-items: center;
        color: var(--dark);
        text-decoration: none;
        transition: all 0.3s;
    }

    .menu-item:hover, 
    .menu-item.active {
        background-color: rgba(211, 47, 47, 0.1);
        color: var(--primary);
    }

    .menu-item i {
        margin-right: 10px;
        width: 20px;
        text-align: center;
    }

    /* Main Content Styles */
    .main-content {
        flex: 1;
        margin-left: 250px;
        padding: 20px;
    }

    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
    }

    .user-info {
        display: flex;
        align-items: center;
    }

    .user-info img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 10px;
    }

    .logout-btn {
        background: var(--primary);
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .logout-btn:hover {
        background: var(--primary-dark);
    }

    /* Page Title */
    .page-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--light-gray);
    }

    .page-title h1 {
        color: var(--primary);
        display: flex;
        align-items: center;
    }

    .page-title h1 i {
        margin-right: 10px;
    }

    .table-actions {
        display: flex;
        gap: 10px;
    }

    /* Button Styles */
    .btn {
        padding: 10px 15px;
        border-radius: 5px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
        border: none;
    }

    .btn-primary:hover {
        background: var(--primary-dark);
    }

    .btn-outline {
        background: white;
        color: var(--primary);
        border: 1px solid var(--primary);
    }

    .btn-outline:hover {
        background: rgba(211, 47, 47, 0.1);
    }

    /* Inventory Table Styles */
    .inventory-table-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid var(--light-gray);
    }

    .table-title {
        font-weight: 600;
        color: var(--dark);
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 15px 20px;
        text-align: left;
        border-bottom: 1px solid var(--light-gray);
    }

    th {
        background-color: #f9f9f9;
        font-weight: 600;
        color: var(--dark);
    }

    tr:hover {
        background-color: #f5f5f5;
    }

    /* Blood Group Indicator */
    .blood-group {
        display: inline-block;
        padding: 5px 10px;
        background-color: var(--primary);
        color: white;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.9em;
    }

    /* Status Indicators */
    .status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.9em;
        font-weight: 500;
    }

    .status.available {
        background-color: rgba(46, 125, 50, 0.1);
        color: var(--secondary);
    }

    .status.critical {
        background-color: rgba(255, 143, 0, 0.1);
        color: var(--warning);
    }

    .status.expired {
        background-color: rgba(211, 47, 47, 0.1);
        color: var(--primary);
    }

    /* Action Buttons */
    .action-btn {
        background: none;
        border: none;
        color: var(--gray);
        cursor: pointer;
        font-size: 1.1em;
        margin: 0 5px;
        transition: color 0.3s;
    }

    .action-btn:hover {
        color: var(--primary);
    }

    .delete-btn:hover {
        color: var(--primary-dark);
    }

    /* Responsive Adjustments */
    @media (max-width: 992px) {
        .admin-sidebar {
            width: 200px;
        }
        .main-content {
            margin-left: 200px;
        }
    }

    @media (max-width: 768px) {
        .admin-sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s;
            z-index: 1000;
        }
        .admin-sidebar.active {
            transform: translateX(0);
        }
        .main-content {
            margin-left: 0;
            padding: 15px;
        }
        
        table {
            display: block;
            overflow-x: auto;
        }
    }
</style>
    </style>
</head>
<body>
    <!-- Sidebar (Same as dashboard.php) -->
    <nav class="admin-sidebar">
        <!-- ... same sidebar content ... -->
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header (Same as dashboard.php) -->
        <header class="admin-header">
            <!-- ... same header content ... -->
        </header>

        <!-- Page Title -->
        <div class="page-title">
            <h1><i class="fas fa-tint"></i> Blood Inventory Management</h1>
            <div class="table-actions">
                <button class="btn btn-outline" id="filter-btn"><i class="fas fa-filter"></i> Filter</button>
                <button class="btn btn-primary" onclick="window.location.href='add-blood-stock.php'">
                    <i class="fas fa-plus"></i> Add New Stock
                </button>
            </div>
        </div>

        <!-- Inventory Table -->
        <div class="inventory-table-container">
            <div class="table-header">
                <div class="table-title">Current Blood Inventory</div>
                <div class="table-actions">
                    <button class="btn btn-outline" id="refresh-table"><i class="fas fa-sync-alt"></i> Refresh</button>
                    <button class="btn btn-outline" id="export-btn"><i class="fas fa-download"></i> Export</button>
                </div>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Blood Group</th>
                        <th>Quantity (Units)</th>
                        <th>Status</th>
                        <th>Last Donation</th>
                        <th>Expiration Date</th>
                        <th>Storage Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($inventory_result->num_rows > 0) {
                        while ($row = $inventory_result->fetch_assoc()) {
                            $status_class = strtolower($row['status']);
                            echo "<tr>
                                <td><span class=\"blood-group\">{$row['blood_group']}</span></td>
                                <td>{$row['quantity']}</td>
                                <td><span class=\"status {$status_class}\">{$row['status']}</span></td>
                                <td>" . date('Y-m-d', strtotime($row['last_donation_date'])) . "</td>
                                <td>" . date('Y-m-d', strtotime($row['expiration_date'])) . "</td>
                                <td>{$row['storage_location']}</td>
                                <td>
                                    <button class=\"action-btn\" onclick=\"window.location.href='edit-blood-stock.php?id={$row['inventory_id']}'\">
                                        <i class=\"fas fa-edit\"></i>
                                    </button>
                                    <button class=\"action-btn delete-btn\" data-id=\"{$row['inventory_id']}\">
                                        <i class=\"fas fa-trash\"></i>
                                    </button>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan=\"7\">No blood inventory found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Delete button functionality
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const inventoryId = this.getAttribute('data-id');
                    if (confirm('Are you sure you want to delete this blood stock?')) {
                        window.location.href = `delete-blood-stock.php?id=${inventoryId}`;
                    }
                });
            });
            
            // Refresh table button
            document.getElementById('refresh-table').addEventListener('click', function() {
                location.reload();
            });
            
            // Export button functionality (simplified - would normally generate CSV)
            document.getElementById('export-btn').addEventListener('click', function() {
                alert('Export functionality would generate a CSV file in a real application');
            });
            
            // Filter button functionality
            document.getElementById('filter-btn').addEventListener('click', function() {
                alert('Filter functionality would open a filter panel in a real application');
            });
        });
    </script>
</body>
</html>