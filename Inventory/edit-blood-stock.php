<?php
require_once 'config.php';


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: blood-inventory.php");
    exit();
}

$inventory_id = intval($_GET['id']);
$error = '';
$success = '';

// Fetch current inventory data
$stmt = $conn->prepare("SELECT * FROM blood_inventory WHERE inventory_id = ?");
$stmt->bind_param("i", $inventory_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: blood-inventory.php");
    exit();
}

$inventory = $result->fetch_assoc();

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
        // Update database
        $stmt = $conn->prepare("UPDATE blood_inventory SET blood_group = ?, quantity = ?, status = ?, last_donation_date = ?, expiration_date = ?, storage_location = ?, notes = ? WHERE inventory_id = ?");
        $stmt->bind_param("sississi", $blood_group, $quantity, $status, $last_donation_date, $expiration_date, $storage_location, $notes, $inventory_id);
        
        if ($stmt->execute()) {
            // Record in history if quantity changed
            if ($quantity != $inventory['quantity']) {
                $history_stmt = $conn->prepare("INSERT INTO inventory_history (inventory_id, action, changed_by, old_quantity, new_quantity, notes) VALUES (?, 'Update', ?, ?, ?, ?)");
                $history_stmt->bind_param("iiiss", $inventory_id, $_SESSION['admin_id'], $inventory['quantity'], $quantity, $notes);
                $history_stmt->execute();
            }
            
            $success = 'Blood stock updated successfully!';
            // Refresh inventory data
            $stmt = $conn->prepare("SELECT * FROM blood_inventory WHERE inventory_id = ?");
            $stmt->bind_param("i", $inventory_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $inventory = $result->fetch_assoc();
        } else {
            $error = 'Error updating blood stock: ' . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Blood Stock | Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* (Same CSS as add-blood-stock.php) */
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
            <h1><i class="fas fa-edit"></i> Edit Blood Stock</h1>
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
            
            <form action="edit-blood-stock.php?id=<?php echo $inventory_id; ?>" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="blood_group">Blood Group</label>
                        <select id="blood_group" name="blood_group" class="form-control" required>
                            <option value="A+" <?php echo ($inventory['blood_group'] == 'A+') ? 'selected' : ''; ?>>A+</option>
                            <option value="A-" <?php echo ($inventory['blood_group'] == 'A-') ? 'selected' : ''; ?>>A-</option>
                            <option value="B+" <?php echo ($inventory['blood_group'] == 'B+') ? 'selected' : ''; ?>>B+</option>
                            <option value="B-" <?php echo ($inventory['blood_group'] == 'B-') ? 'selected' : ''; ?>>B-</option>
                            <option value="AB+" <?php echo ($inventory['blood_group'] == 'AB+') ? 'selected' : ''; ?>>AB+</option>
                            <option value="AB-" <?php echo ($inventory['blood_group'] == 'AB-') ? 'selected' : ''; ?>>AB-</option>
                            <option value="O+" <?php echo ($inventory['blood_group'] == 'O+') ? 'selected' : ''; ?>>O+</option>
                            <option value="O-" <?php echo ($inventory['blood_group'] == 'O-') ? 'selected' : ''; ?>>O-</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="quantity">Quantity (Units)</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" min="1" value="<?php echo htmlspecialchars($inventory['quantity']); ?>" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="Available" <?php echo ($inventory['status'] == 'Available') ? 'selected' : ''; ?>>Available</option>
                            <option value="Low" <?php echo ($inventory['status'] == 'Low') ? 'selected' : ''; ?>>Low</option>
                            <option value="Critical" <?php echo ($inventory['status'] == 'Critical') ? 'selected' : ''; ?>>Critical</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="storage_location">Storage Location</label>
                        <input type="text" id="storage_location" name="storage_location" class="form-control" value="<?php echo htmlspecialchars($inventory['storage_location']); ?>" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="last_donation_date">Last Donation Date</label>
                        <input type="date" id="last_donation_date" name="last_donation_date" class="form-control" value="<?php echo htmlspecialchars($inventory['last_donation_date']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="expiration_date">Expiration Date</label>
                        <input type="date" id="expiration_date" name="expiration_date" class="form-control" value="<?php echo htmlspecialchars($inventory['expiration_date']); ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea id="notes" name="notes" class="form-control" rows="3"><?php echo htmlspecialchars($inventory['notes']); ?></textarea>
                </div>
                
                <div class="btn-container">
                    <button type="button" class="btn btn-outline" onclick="window.location.href='blood-inventory.php'">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Blood Stock</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>