<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['bbdmsdid']) == 0) {
    header('location:logout.php');
} else {
   if (isset($_POST['update'])) {
    $uid      = $_SESSION['bbdmsdid'];
    $name     = $_POST['fullname'];
    $mno      = $_POST['mobileno'];
    $age      = $_POST['age'];
    $gender   = $_POST['gender'];
    $latitude = $_POST['latitude'];
    $longitude= $_POST['longitude'];
    $message  = $_POST['message'];

    $targetDir = "uploads/";
    $profileImageSQL = ""; // will add this if image is updated

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

    $sql = "UPDATE tblblooddonars 
            SET FullName=:name, MobileNumber=:mno, Age=:age, Gender=:gender, 
                latitude=:latitude, longitude=:longitude, 
                Message=:message $profileImageSQL
            WHERE id=:uid";

    $query = $dbh->prepare($sql);
    $query->bindParam(':name', $name, PDO::PARAM_STR);
    $query->bindParam(':mno', $mno, PDO::PARAM_STR);
    $query->bindParam(':age', $age, PDO::PARAM_STR);
    $query->bindParam(':gender', $gender, PDO::PARAM_STR);
    $query->bindParam(':latitude', $latitude, PDO::PARAM_STR);
    $query->bindParam(':longitude', $longitude, PDO::PARAM_STR);
    $query->bindParam(':message', $message, PDO::PARAM_STR);
    $query->bindParam(':uid', $uid, PDO::PARAM_STR);

    if (!empty($profileImageSQL)) {
        $query->bindParam(':image', $uniqueName, PDO::PARAM_STR);
    }

    if ($query->execute()) {
        echo '<script>alert("Profile has been updated successfully!");window.location="profile.php";</script>';
    } else {
        echo '<script>alert("Something went wrong. Please try again.");</script>';
    }
}
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>My Profile | Life Savior</title>
        <meta name="description" content="Manage your donor profile and personal information">

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

            ul {
                padding-left: 0;
                list-style: none;
            }

            li {
                list-style: none;
            }

            a {
                text-decoration: none;
            }

            body {
                font-family: 'Open Sans', sans-serif;
                color: #333;
                line-height: 1.6;
            }

            h1,
            h2,
            h3,
            h4,
            h5,
            h6 {
                font-family: 'Poppins', sans-serif;
                font-weight: 600;
            }

            .hero-banner {
                background: linear-gradient(rgba(29, 53, 87, 0.8), rgba(29, 53, 87, 0.8)), url('images/profile-hero.jpg');
                background-size: cover;
                background-position: center;
                color: white;
                padding: 100px 0;
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
                padding: 80px 0;
            }

            .profile-card {
                background: white;
                border-radius: 10px;
                padding: 40px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
                max-width: 800px;
                margin: 0 auto;
            }

            .section-title {
                position: relative;
                margin-bottom: 30px;
                text-align: center;
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

            .form-control,
            .form-select {
                padding: 12px 15px;
                margin-bottom: 20px;
                border: 1px solid #ddd;
                border-radius: 5px;
                background-color: #fff;
            }

            .form-control:focus,
            .form-select:focus {
                border-color: var(--primary);
                box-shadow: 0 0 0 0.25rem rgba(230, 57, 70, 0.25);
            }

            .form-control[readonly] {
                background-color: #f8f9fa;
            }

            textarea.form-control {
                min-height: 100px;
                resize: none;
            }

            .btn-update {
                background-color: var(--primary);
                border-color: var(--primary);
                padding: 12px 30px;
                font-weight: 600;
                transition: all 0.3s;
                width: 100%;
            }

            .btn-update:hover {
                background-color: #c1121f;
                border-color: #c1121f;
                transform: translateY(-2px);
            }

            .required-field::after {
                content: " *";
                color: var(--primary);
            }

            .profile-header {
                display: flex;
                align-items: center;
                margin-bottom: 30px;
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

            .footer {
                background-color: var(--secondary);
                color: white;
                padding: 60px 0 20px;
            }

            .footer-links h5 {
                margin-bottom: 20px;
                font-weight: 600;
            }

            .footer-links ul {
                list-style: none;
                padding-left: 0;
            }

            .footer-links li {
                margin-bottom: 10px;
            }

            .footer-links a {
                color: rgba(255, 255, 255, 0.7);
                text-decoration: none;
                transition: color 0.3s;
            }

            .footer-links a:hover {
                color: white;
            }

            .social-icons a {
                display: inline-block;
                width: 40px;
                height: 40px;
                background-color: rgba(255, 255, 255, 0.1);
                border-radius: 50%;
                text-align: center;
                line-height: 40px;
                margin-right: 10px;
                color: white;
                transition: all 0.3s;
            }

            .social-icons a:hover {
                background-color: var(--primary);
                transform: translateY(-3px);
            }

            .copyright {
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                padding-top: 20px;
                margin-top: 40px;
                text-align: center;
                opacity: 0.7;
            }

            .emergency-banner {
                background-color: var(--primary);
                color: white;
                padding: 15px 0;
                text-align: center;
                font-weight: 600;
                margin-bottom: 0;
            }

            @media (max-width: 768px) {
                .hero-title {
                    font-size: 2.5rem;
                }

                .hero-subtitle {
                    font-size: 1.2rem;
                }
            }
        </style>
    </head>

    <body>
        <?php include('includes/header.php'); ?>

        <!-- Hero Banner -->
        <section class="hero-banner">
            <div class="container">
                <h1>Your Donor Profile</h1>
                <p class="lead">Manage your personal information and preferences</p>
            </div>
        </section>

        <!-- Breadcrumb -->
        <div class="breadcrumb-container">
            <div class="container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Donor Profile</li>
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
                    $sql = "SELECT * from tblblooddonars where id=:uid";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':uid', $uid, PDO::PARAM_STR);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);

                    if ($query->rowCount() > 0) {
                        foreach ($results as $row) {
                            ?>
                            <div class="profile-header">
                                <div class="profile-avatar">
                                    <?php if (!empty($row->profile_image)) { ?>
                                        <img src="uploads/<?php echo trim($row->profile_image); ?>" alt="Profile Image"
                                            style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;">
                                    <?php } else { ?>
                                        <i class="fas fa-user"></i>
                                    <?php } ?>
                                </div>
                                <div class="profile-info">
                                    <h3><?php echo htmlentities($row->FullName); ?></h3>
                                    <p><?php echo htmlentities($row->BloodGroup); ?> Donor</p>
                                    <span class="status-badge">Active Donor</span>
                                </div>
                            </div>

                            <h2 class="section-title">Update Your Profile</h2>

                            <form action="" method="post" enctype="multipart/form-data">

                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="fullname" class="form-label required-field">Full Name</label>
                                        <input type="text" class="form-control" name="fullname" id="fullname"
                                            value="<?php echo htmlentities($row->FullName); ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="mobileno" class="form-label required-field">Mobile Number</label>
                                        <input type="tel" class="form-control" name="mobileno" id="mobileno" required maxlength="10"
                                            pattern="[0-9]+" value="<?php echo htmlentities($row->MobileNumber); ?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="emailid" class="form-label required-field">Email Address</label>
                                        <input type="email" name="emailid" class="form-control"
                                            value="<?php echo htmlentities($row->EmailId); ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="age" class="form-label required-field">Age</label>
                                        <input type="number" class="form-control" name="age" id="age" required min="18" max="65"
                                            value="<?php echo htmlentities($row->Age); ?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="gender" class="form-label required-field">Gender</label>
                                        <select name="gender" class="form-select" required>
                                            <option value="<?php echo htmlentities($row->Gender); ?>">
                                                <?php echo htmlentities($row->Gender); ?>
                                            </option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="bloodgroup" class="form-label">Blood Group</label>
                                        <select name="bloodgroup" disabled class="form-select" required>
                                            <option value="<?php echo htmlentities($row->BloodGroup); ?>">
                                                <?php echo htmlentities($row->BloodGroup); ?>
                                            </option>
                                            <?php
                                            $sql = "SELECT * from tblbloodgroup";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                            if ($query->rowCount() > 0) {
                                                foreach ($results as $result) {
                                                    ?>
                                                    <option value="<?php echo htmlentities($result->BloodGroup); ?>">
                                                        <?php echo htmlentities($result->BloodGroup); ?>
                                                    </option>
                                                <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="address" class="form-label required-field">Address</label>
                                    <div id="map" style="height: 300px; border-radius: 10px;"></div>
                                </div>

                                <!-- Hidden Lat/Lng -->
                                <input type="hidden" name="latitude" id="latitude"
                                    value="<?php echo htmlentities($row->latitude); ?>">
                                <input type="hidden" name="longitude" id="longitude"
                                    value="<?php echo htmlentities($row->longitude); ?>">

                                <div class="form-group">
                                    <label for="message" class="form-label">Personal Message</label>
                                    <textarea class="form-control" name="message"
                                        placeholder="Tell us why you donate blood"><?php echo htmlentities($row->Message); ?></textarea>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="profile_image" class="form-label required-field">Upload Profile Photo</label>
                                    <input type="file" class="form-control" name="profile_image" accept="image/*">

                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" name="update" disabled id="updateBtn" class="btn btn-update text-white">
                                        <i class="fas fa-save me-2"></i> Update Profile
                                    </button>
                                </div>
                            </form>
                        <?php }
                    } ?>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 mb-4 mb-lg-0">
                        <h5>Life Savior</h5>
                        <p class="mt-3">Our mission is to connect blood donors with recipients quickly and efficiently,
                            saving lives one donation at a time.</p>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                        <h5>Quick Links</h5>
                        <ul class="mt-3">
                            <li><a href="index.php">Home</a></li>
                            <li><a href="donor-list.php">Find Donors</a></li>
                            <?php if (!isset($_SESSION['bbdmsdid'])) {
                                ?>
                                <li><a href="sign-up.php">Become Donor</a></li>
                            <?php } ?>
                            <li><a href="about.php">About</a></li>
                            <li><a href="contact.php">Contact</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                        <h5>Our Location</h5>
                        <div class="map-responsive mb-4">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3559.9940459226576!2d75.81897407524825!3d26.84213516207256!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x396db51861c1ad9d%3A0xb387a4d4505bb4b!2sSMS%20Hospital%2C%20Jaipur!5e0!3m2!1sen!2sin!4v1628311460300!5m2!1sen!2sin"
                                width="100%" height="250" style="border:0; border-radius: 10px;" allowfullscreen=""
                                loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                        <style>
                            .map-responsive {
                                position: relative;
                                padding-bottom: 56.25%;
                                height: 0;
                                overflow: hidden;
                                border-radius: 10px;
                            }

                            .map-responsive iframe {
                                position: absolute;
                                top: 0;
                                left: 0;
                                width: 100%;
                                height: 100%;
                                border: 0;
                            }
                        </style>

                    </div>
                    <div class="col-lg-4">
                        <h5>Contact Info</h5>
                        <ul class="mt-3">
                            <li><i class="fas fa-map-marker-alt me-2"></i> 123 Health St, Medical City</li>
                            <li><i class="fas fa-phone me-2"></i> +1 234 567 8900</li>
                            <li><i class="fas fa-envelope me-2"></i> info@bloodbank.com</li>
                            <li><i class="fas fa-clock me-2"></i> 24/7 Emergency Service</li>
                        </ul>
                    </div>
                </div>
                <div class="copyright">
                    <p class="mb-0">&copy; <?php echo date("Y"); ?> Life Savior. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>

        <!-- Scripts -->

        <!-- Handles Map Logic -->
        <script>
            const latInput = document.getElementById("latitude");
            const lngInput = document.getElementById("longitude");

            const defaultLat = parseFloat(latInput.value) || 22.8167;
            const defaultLng = parseFloat(lngInput.value) || 70.8333;

            const map = L.map('map').setView([defaultLat, defaultLng], 14);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            const marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

            // Update inputs on marker drag
            marker.on('dragend', function (e) {
                const pos = marker.getLatLng();
                latInput.value = pos.lat.toFixed(6);
                lngInput.value = pos.lng.toFixed(6);
            });

            // Move marker & update inputs on map click
            map.on('click', function (e) {
                marker.setLatLng(e.latlng);
                latInput.value = e.latlng.lat.toFixed(6);
                lngInput.value = e.latlng.lng.toFixed(6);
            });
        </script>

        <!-- Handles Updation Logic -->
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const form = document.querySelector("form");
                const updateBtn = document.getElementById("updateBtn");

                let initialData = new FormData(form); // Can be updated later

                function isFormChanged() {
                    const currentData = new FormData(form);

                    for (let [key, value] of currentData.entries()) {
                        const initialValue = initialData.get(key) || "";
                        const currentValue = value || "";

                        if (initialValue !== currentValue) {
                            return true;
                        }
                    }
                    return false;
                }

                function updateButtonState() {
                    const allEmpty = Array.from(new FormData(form).values()).every(v => v.trim() === "");
                    updateBtn.disabled = !isFormChanged() || allEmpty;
                }

                updateButtonState();

                form.addEventListener("input", updateButtonState);
                form.addEventListener("change", updateButtonState);

                // After successful update → refresh initial data
                form.addEventListener("submit", function (e) {
                    // e.preventDefault(); // Remove if you're not using AJAX

                    // Example: AJAX call simulation
                    setTimeout(() => {
                        // Save the current form values as new "initial"
                        initialData = new FormData(form);
                        updateButtonState();
                        alert("Data updated successfully!");
                    }, 500);
                });
            });
        </script>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="js/jquery-3.6.0.min.js"></script>
    </body>

    </html>
<?php } ?>