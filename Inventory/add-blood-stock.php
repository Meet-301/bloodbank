<?php
require_once 'config.php';


$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $blood_group = trim($_POST['blood_group']);
    $quantity = intval(trim($_POST['quantity']));
    $status = trim($_POST['status']);
    $last_donation_date = trim($_POST['last_donation_date']);
    $expiration_date = trim($_POST['expiration_date']);
    $storage_location = trim($_POST['storage_location']);
    $notes = trim($_POST['notes']);
    
    // Validate inputs
    if (empty($blood_group)) {
        $error = 'Blood group is required';
    } elseif ($quantity <= 0) {
        $error = 'Quantity must be greater than 0';
    } elseif (empty($last_donation_date) || empty($expiration_date)) {
        $error = 'Dates are required';
    } elseif (empty($storage_location)) {
        $error = 'Storage location is required';
    } else {
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO blood_inventory (blood_group, quantity, status, last_donation_date, expiration_date, storage_location, notes) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sississ", $blood_group, $quantity, $status, $last_donation_date, $expiration_date, $storage_location, $notes);
        
        if ($stmt->execute()) {
            // Record in history
            $inventory_id = $stmt->insert_id;
            $history_stmt = $conn->prepare("INSERT INTO inventory_history (inventory_id, action, changed_by, old_quantity, new_quantity, notes) VALUES (?, 'Add', ?, 0, ?, ?)");
            $history_stmt->bind_param("iiis", $inventory_id, $_SESSION['admin_id'], $quantity, $notes);
            $history_stmt->execute();
            
            $success = 'Blood stock added successfully!';
            $_POST = []; // Clear form
        } else {
            $error = 'Error adding blood stock: ' . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Blood Stock | Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* (Keep all the CSS from the original dashboard.php) */
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
        
        .btn-container {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
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
            <h1><i class="fas fa-plus-circle"></i> Add New Blood Stock</h1>
            <button class="btn btn-outline" onclick="window.location.href='blood-inventory.php'">
                <i class="fas fa-arrow-left"></i> Back to Inventory
            </button>
        </div>

        <!-- Form Container -->
        <div class="form-container">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <form action="add-blood-stock.php" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="blood_group">Blood Group</label>
                        <select id="blood_group" name="blood_group" class="form-control" required>
                            <option value="">Select Blood Group</option>
                            <option value="A+" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'A+') ? 'selected' : ''; ?>>A+</option>
                            <option value="A-" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'A-') ? 'selected' : ''; ?>>A-</option>
                            <option value="B+" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'B+') ? 'selected' : ''; ?>>B+</option>
                            <option value="B-" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'B-') ? 'selected' : ''; ?>>B-</option>
                            <option value="AB+" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'AB+') ? 'selected' : ''; ?>>AB+</option>
                            <option value="AB-" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'AB-') ? 'selected' : ''; ?>>AB-</option>
                            <option value="O+" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'O+') ? 'selected' : ''; ?>>O+</option>
                            <option value="O-" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'O-') ? 'selected' : ''; ?>>O-</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="quantity">Quantity (Units)</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" min="1" value="<?php echo isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : ''; ?>" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="Available" <?php echo (isset($_POST['status']) && $_POST['status'] == 'Available') ? 'selected' : ''; ?>>Available</option>
                            <option value="Low" <?php echo (isset($_POST['status']) && $_POST['status'] == 'Low') ? 'selected' : ''; ?>>Low</option>
                            <option value="Critical" <?php echo (isset($_POST['status']) && $_POST['status'] == 'Critical') ? 'selected' : ''; ?>>Critical</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="storage_location">Storage Location</label>
                        <input type="text" id="storage_location" name="storage_location" class="form-control" value="<?php echo isset($_POST['storage_location']) ? htmlspecialchars($_POST['storage_location']) : ''; ?>" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="last_donation_date">Last Donation Date</label>
                        <input type="date" id="last_donation_date" name="last_donation_date" class="form-control" value="<?php echo isset($_POST['last_donation_date']) ? htmlspecialchars($_POST['last_donation_date']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="expiration_date">Expiration Date</label>
                        <input type="date" id="expiration_date" name="expiration_date" class="form-control" value="<?php echo isset($_POST['expiration_date']) ? htmlspecialchars($_POST['expiration_date']) : ''; ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea id="notes" name="notes" class="form-control" rows="3"><?php echo isset($_POST['notes']) ? htmlspecialchars($_POST['notes']) : ''; ?></textarea>
                </div>
                
                <div class="btn-container">
                    <button type="reset" class="btn btn-outline">Reset</button>
                    <button type="submit" class="btn btn-primary">Add Blood Stock</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>