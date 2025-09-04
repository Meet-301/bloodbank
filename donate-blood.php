<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('includes/config.php');

// Check login
if (!isset($_SESSION['donorid'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission
if (isset($_POST['submit'])) {
    $donorId = $_SESSION['donorid'];
    $donationDate = $_POST['donation_date'];
    $bloodGroup = $_POST['bloodgroup'];
    $units = $_POST['units'];
    $hospital = $_POST['hospital'];
    $location = $_POST['location'];
    $remarks = $_POST['remarks'];
    $status = 0;

    try {
        $sql = "INSERT INTO tblblooddonations (DonorID, DonationDate, BloodGroup, UnitsDonated, HospitalName, Location, Remarks, status)
                VALUES (:donorId, :donationDate, :bloodGroup, :units, :hospital, :location, :remarks, :status)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':donorId', $donorId, PDO::PARAM_INT);
        $query->bindParam(':donationDate', $donationDate, PDO::PARAM_STR);
        $query->bindParam(':bloodGroup', $bloodGroup, PDO::PARAM_STR);
        $query->bindParam(':units', $units, PDO::PARAM_STR);
        $query->bindParam(':hospital', $hospital, PDO::PARAM_STR);
        $query->bindParam(':location', $location, PDO::PARAM_STR);
        $query->bindParam(':remarks', $remarks, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_INT);
        $query->execute();

        echo "<script>alert('Blood donation recorded successfully!');</script>";
        echo "<script>window.location.href='dashboard.php';</script>";
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donate Blood | Life Savior</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f8f9fa; }
        .donate-section { padding: 80px 0; }
        .donate-card {
            background: #fff; padding: 40px; border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            max-width: 700px; margin: auto;
        }
        .section-title { text-align: center; margin-bottom: 30px; }
        .btn-donate {
            background-color: #e63946; color: white;
            padding: 12px 30px; font-weight: 600; border: none;
            transition: all 0.3s ease; width: 100%;
        }
        .btn-donate:hover { background-color: #c1121f; }
        .required::after {
            content: "*"; color: red; margin-left: 4px;
        }
    </style>
</head>
<body>

<section class="donate-section">
    <div class="container">
        <div class="donate-card">
            <h2 class="section-title">Blood Donation Form</h2>

            <form method="post">
                <div class="mb-3">
                    <label class="form-label required">Date of Donation</label>
                    <input type="date" name="donation_date" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label required">Blood Group</label>
                    <select name="bloodgroup" class="form-select" required>
                        <option value="">-- Select Blood Group --</option>
                        <?php
                        $sql = "SELECT * FROM tblbloodgroup";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                        foreach ($results as $row) {
                            echo "<option value='" . htmlentities($row->BloodGroup) . "'>" . htmlentities($row->BloodGroup) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label required">Units Donated (in ml)</label>
                    <input type="number" name="units" class="form-control" placeholder="e.g., 450" min="100" required>
                </div>

                <div class="mb-3">
                    <label class="form-label required">Hospital Name</label>
                    <input type="text" name="hospital" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control" placeholder="e.g., City or Area">
                </div>

                <div class="mb-3">
                    <label class="form-label">Remarks (optional)</label>
                    <textarea name="remarks" class="form-control" placeholder="Any additional notes..."></textarea>
                </div>

                <button type="submit" name="submit" class="btn btn-donate">
                    <i class="fas fa-heart me-2"></i>Submit Donation
                </button>
            </form>
        </div>
    </div>
</section>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Font Awesome (for heart icon) -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
