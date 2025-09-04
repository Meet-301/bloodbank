<?php
require_once 'config.php';


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: blood-inventory.php");
    exit();
}

$inventory_id = intval($_GET['id']);

// Fetch current inventory data for history
$stmt = $conn->prepare("SELECT * FROM blood_inventory WHERE inventory_id = ?");
$stmt->bind_param("i", $inventory_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $inventory = $result->fetch_assoc();
    
    // Record in history before deleting
    $history_stmt = $conn->prepare("INSERT INTO inventory_history (inventory_id, action, changed_by, old_quantity, new_quantity, notes) VALUES (?, 'Delete', ?, ?, 0, 'Blood stock deleted')");
    $history_stmt->bind_param("iii", $inventory_id, $_SESSION['admin_id'], $inventory['quantity']);
    $history_stmt->execute();
    
    // Delete the record
    $delete_stmt = $conn->prepare("DELETE FROM blood_inventory WHERE inventory_id = ?");
    $delete_stmt->bind_param("i", $inventory_id);
    $delete_stmt->execute();
}

header("Location: blood-inventory.php?deleted=1");
exit();
?>