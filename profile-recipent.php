<?php
session_start();
error_reporting(0);
ini_set('display_errors', 1);
include('includes/config.php');

if (strlen($_SESSION['bbdmsdid']) == 0) {
    header('location:logout-recipient.php');
    exit();
} 

if (isset($_POST['update'])) {
    $uid      = $_SESSION['bbdmsdid'];
    $name     = $_POST['fullname'];
    $mno      = $_POST['mobileno'];
    $email    = $_POST['emailid'];
    $age      = $_POST['age'];
    $gender   = $_POST['gender'];
    $latitude = $_POST['latitude'];
    $longitude= $_POST['longitude'];
    $message  = $_POST['message'];

    $targetDir = "uploads/";
    $profileImageSQL = "";
    $uniqueName = "";

    if (!empty($_FILES["profile_image"]["name"])) {
        $fileName  = basename($_FILES["profile_image"]["name"]);
        $uniqueName= uniqid() . "_" . $fileName;
        $targetFile= $targetDir . $uniqueName;

        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFile)) {
            $profileImageSQL = ", profile_image=:image";
        } else {
            echo "<script>alert('Error uploading image');</script>";
        }
    }

    $sql = "UPDATE tblrecipients 
            SET FullName=:name, MobileNumber=:mno, EmailId=:email, Age=:age, Gender=:gender, 
                latitude=:latitude, longitude=:longitude, 
                Message=:message $profileImageSQL
            WHERE id=:uid";

    $query = $dbh->prepare($sql);
    $query->bindParam(':name', $name, PDO::PARAM_STR);
    $query->bindParam(':mno', $mno, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':age', $age, PDO::PARAM_INT);
    $query->bindParam(':gender', $gender, PDO::PARAM_STR);
    $query->bindParam(':latitude', $latitude, PDO::PARAM_STR);
    $query->bindParam(':longitude', $longitude, PDO::PARAM_STR);
    $query->bindParam(':message', $message, PDO::PARAM_STR);
    $query->bindParam(':uid', $uid, PDO::PARAM_INT);

    if (!empty($profileImageSQL)) {
        $query->bindParam(':image', $uniqueName, PDO::PARAM_STR);
    }

    if ($query->execute()) {
        echo '<script>alert("Profile has been updated successfully!");window.location="profile-recipent.php";</script>';
        exit();
    } else {
        echo '<script>alert("Something went wrong. Please try again.");</script>';
        error_log("Database error: " . implode(":", $query->errorInfo()));
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | Life Savior</title>
    <meta name="description" content="Manage your recipient profile and personal information">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Favicon -->
    <link rel="icon" href="images/icon.jpg" type="image/jpeg">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Open+Sans:wght@400;600;700&display=swap"
        rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary: #e63946;
            --secondary: #1d3557;
            --light: #f1faee;
            --accent: #a8dadc;
            --dark: #457b9d;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            color: #333;
            line-height: 1.6;
            background-color: #f8f9fa;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
        }

        .hero-banner {
            background: linear-gradient(rgba(29, 53, 87, 0.8), rgba(29, 53, 87, 0.8)), url('images/profile-hero.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
            text-align: center;
        }

        .breadcrumb-container {
            background-color: var(--light);
            padding: 15px 0;
        }

        .breadcrumb-item.active {
            color: var(--primary);
            font-weight: 600;
        }

        .profile-section {
            padding: 60px 0;
        }

        .profile-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            max-width: 900px;
            margin: 0 auto;
        }

        .section-title {
            position: relative;
            margin-bottom: 25px;
            text-align: center;
            color: var(--secondary);
        }

        .section-title:after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: var(--primary);
            margin: 15px auto;
        }

        .form-label {
            font-weight: 600;
            color: var(--secondary);
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(230, 57, 70, 0.25);
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        .btn-update {
            background-color: var(--primary);
            border-color: var(--primary);
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s;
            width: 100%;
            color: white;
        }

        .btn-update:hover {
            background-color: #c1121f;
            border-color: #c1121f;
            transform: translateY(-2px);
            color: white;
        }

        .required-field::after {
            content: " *";
            color: var(--primary);
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            overflow: hidden;
        }

        .profile-avatar i {
            font-size: 3rem;
            color: var(--secondary);
        }

        .profile-info h3 {
            margin-bottom: 5px;
            color: var(--secondary);
        }

        .profile-info p {
            margin-bottom: 0;
            color: var(--primary);
            font-weight: 500;
        }

        .status-badge {
            background-color: var(--accent);
            color: var(--secondary);
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        #map {
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .map-instructions {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
            }
            
            .profile-avatar {
                margin-right: 0;
                margin-bottom: 15px;
            }
            
            .hero-banner {
                padding: 60px 0;
            }
            
            .profile-card {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <?php include('includes/header-recipient.php'); ?>

    <!-- Hero Banner -->
    <section class="hero-banner">
        <div class="container">
            <h1>Your Recipient Profile</h1>
            <p class="lead">Manage your personal information and location</p>
        </div>
    </section>

    <!-- Breadcrumb -->
    <div class="breadcrumb-container">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index-recipient.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Recipient Profile</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Profile Section -->
    <section class="profile-section">
        <div class="container">
            <div class="profile-card">
                <?php
                $uid = $_SESSION['bbdmsdid'];
                $sql = "SELECT * FROM tblrecipients WHERE id=:uid";
                $query = $dbh->prepare($sql);
                $query->bindParam(':uid', $uid, PDO::PARAM_INT);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);

                if ($query->rowCount() > 0) {
                    foreach ($results as $row) {
                        ?>
                        <div class="profile-header">
                            <div class="profile-avatar">
                                <?php if (!empty($row->profile_image)) { ?>
                                    <img src="uploads/<?php echo htmlentities(trim($row->profile_image)); ?>" alt="Profile Image"
                                        style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;">
                                <?php } else { ?>
                                    <i class="fas fa-user"></i>
                                <?php } ?>
                            </div>
                            <div class="profile-info">
                                <h3><?php echo htmlentities($row->FullName); ?></h3>
                                <p>Recipient</p>
                                <span class="status-badge"><?php echo ($row->status == 1) ? 'Active' : 'Inactive'; ?></span>
                            </div>
                        </div>

                        <h2 class="section-title">Update Your Profile</h2>

                        <form action="" method="post" enctype="multipart/form-data" id="profileForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="fullname" class="form-label required-field">Full Name</label>
                                    <input type="text" class="form-control" name="fullname" id="fullname"
                                        value="<?php echo htmlentities($row->FullName); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="mobileno" class="form-label required-field">Mobile Number</label>
                                    <input type="tel" class="form-control" name="mobileno" id="mobileno" required maxlength="10"
                                        pattern="[0-9]{10}" value="<?php echo htmlentities($row->MobileNumber); ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label for="emailid" class="form-label required-field">Email Address</label>
                                    <input type="email" name="emailid" class="form-control" id="emailid"
                                        value="<?php echo htmlentities($row->EmailId); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="age" class="form-label required-field">Age</label>
                                    <input type="number" class="form-control" name="age" id="age" required min="1" max="120"
                                        value="<?php echo htmlentities($row->Age); ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label for="gender" class="form-label required-field">Gender</label>
                                    <select name="gender" class="form-select" id="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male" <?php echo ($row->Gender == 'Male') ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?php echo ($row->Gender == 'Female') ? 'selected' : ''; ?>>Female</option>
                                        <option value="Other" <?php echo ($row->Gender == 'Other') ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="map" class="form-label required-field">Location</label>
                                <p class="map-instructions">Click on the map to set your location or drag the marker to your exact position</p>
                                <div id="map" style="height: 300px;"></div>
                            </div>

                            <!-- Hidden Lat/Lng -->
                            <input type="hidden" name="latitude" id="latitude"
                                value="<?php echo !empty($row->latitude) ? htmlentities($row->latitude) : '26.9124'; ?>">
                            <input type="hidden" name="longitude" id="longitude"
                                value="<?php echo !empty($row->longitude) ? htmlentities($row->longitude) : '75.7873'; ?>">

                            <div class="form-group">
                                <label for="message" class="form-label">Additional Information</label>
                                <textarea class="form-control" name="message" id="message"
                                    placeholder="Any additional information that might help donors"><?php echo htmlentities($row->Message); ?></textarea>
                            </div>
                            
                            <div class="form-group mt-3">
                                <label for="profile_image" class="form-label">Update Profile Photo</label>
                                <input type="file" class="form-control" name="profile_image" id="profile_image" accept="image/*">
                                <small class="form-text text-muted">Max file size: 2MB. Accepted formats: JPG, PNG, GIF</small>
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" name="update" id="updateBtn" class="btn btn-update">
                                    <i class="fas fa-save me-2"></i> Update Profile
                                </button>
                            </div>
                        </form>
                    <?php }
                } else {
                    echo "<div class='alert alert-danger'>Profile not found. Please contact support.</div>";
                } ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Life Savior</h5>
                    <p class="mt-3">Our mission is to connect blood donors with recipients quickly and efficiently,
                        saving lives one donation at a time.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h5>Contact Info</h5>
                    <ul class="mt-3">
                        <li><i class="fas fa-map-marker-alt me-2"></i> 123 Health St, Medical City</li>
                        <li><i class="fas fa-phone me-2"></i> +1 234 567 8900</li>
                        <li><i class="fas fa-envelope me-2"></i> info@lifesavior.com</li>
                    </ul>
                </div>
            </div>
            <div class="copyright mt-4 pt-3">
                <p class="mb-0 text-center">&copy; <?php echo date("Y"); ?> Life Savior. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Handles Map Logic -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const latInput = document.getElementById("latitude");
            const lngInput = document.getElementById("longitude");
            
            // Default to Jaipur if no coordinates are set
            const defaultLat = parseFloat(latInput.value) || 26.9124;
            const defaultLng = parseFloat(lngInput.value) || 75.7873;

            const map = L.map('map').setView([defaultLat, defaultLng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            const marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

            // Update inputs on marker drag
            marker.on('dragend', function(e) {
                const pos = marker.getLatLng();
                latInput.value = pos.lat.toFixed(6);
                lngInput.value = pos.lng.toFixed(6);
            });

            // Move marker & update inputs on map click
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                latInput.value = e.latlng.lat.toFixed(6);
                lngInput.value = e.latlng.lng.toFixed(6);
            });
            
            // Form validation
            const form = document.getElementById('profileForm');
            form.addEventListener('submit', function(e) {
                let valid = true;
                const requiredFields = form.querySelectorAll('[required]');
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        valid = false;
                        field.classList.add('is-invalid');
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });
                
                // Mobile number validation
                const mobileInput = document.getElementById('mobileno');
                if (mobileInput.value && !/^\d{10}$/.test(mobileInput.value)) {
                    valid = false;
                    mobileInput.classList.add('is-invalid');
                }
                
                if (!valid) {
                    e.preventDefault();
                    alert('Please fill all required fields correctly.');
                }
            });
        });
    </script>
</body>

</html>