<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (isset($_POST['submit'])) {
    $fullname = $_POST['fullname'];
    $mobile = $_POST['mobileno'];
    $email = $_POST['emailid'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $bloodgroup = $_POST['bloodgroup'];
    $lat = $_POST['latitude'];
    $lng = $_POST['longitude'];
    $message = $_POST['message'];
    $status = 0;
    $password = md5($_POST['password']);

    // Handle image upload
    $profileImage = null;

    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileTmpPath = $_FILES['profileImage']['tmp_name'];
        $fileType = mime_content_type($fileTmpPath);

        if (in_array($fileType, $allowedTypes)) {
            // Give unique name to avoid overwriting
            $imageName = uniqid() . '_' . basename($_FILES['profileImage']['name']);
            $uploadDir = 'uploads/'; // Make sure this folder exists and is writable
            $destPath = $uploadDir . $imageName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $profileImage = $imageName; // Save this in DB
            } else {
                echo "<script>alert('Failed to move uploaded file.');</script>";
                exit();
            }
        } else {
            echo "<script>alert('Only JPG, PNG, and GIF images are allowed.');</script>";
            exit();
        }
    }

    $ret = "SELECT EmailId FROM tblrecipients WHERE EmailId=:email";
    $query = $dbh->prepare($ret);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() == 0) {
        $sql = "INSERT INTO tblrecipients(FullName, MobileNumber, EmailId, Age, Gender, BloodGroup, latitude, longitude, Message, status, Password, profile_image) 
                VALUES(:fullname, :mobile, :email, :age, :gender, :bloodgroup, :lat, :lng, :message, :status, :password, :profile_image)";

        $query = $dbh->prepare($sql);
        $query->bindParam(':fullname', $fullname, PDO::PARAM_STR);
        $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':age', $age, PDO::PARAM_STR);
        $query->bindParam(':gender', $gender, PDO::PARAM_STR);
        $query->bindParam(':bloodgroup', $bloodgroup, PDO::PARAM_STR);
        $query->bindParam(':lat', $lat, PDO::PARAM_STR);
        $query->bindParam(':lng', $lng, PDO::PARAM_STR);
        $query->bindParam(':message', $message, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->bindParam(':profile_image', $profileImage, PDO::PARAM_STR);

        $query->execute();
        $lastInsertId = $dbh->lastInsertId();

         if($lastInsertId) {
            echo "<script>alert('You have signed up successfully!');</script>";
            echo "<script type='text/javascript'> document.location ='login-recipient.php'; </script>";
        } else {
            echo "<script>alert('Something went wrong. Please try again');</script>";
        }
    } else {
        echo "<script>alert('Email-id already exists. Please try again');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register as Recipient | Life Savior</title>
    <meta name="description" content="Register as a blood recipient to find donors when you need them">

    <!-- Favicon -->
    <link rel="icon" href="images/icon.jpg" type="image/jpeg">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

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
            background: linear-gradient(rgba(29, 53, 87, 0.8), rgba(29, 53, 87, 0.8)), url('images/hero-bg.jpg');
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

        .signup-section {
            padding: 80px 0;
        }

        .signup-card {
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
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(230, 57, 70, 0.25);
        }

        textarea.form-control {
            min-height: 100px;
            resize: none;
        }

        .btn-register {
            background-color: var(--primary);
            border-color: var(--primary);
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s;
            width: 100%;
        }

        .btn-register:hover {
            background-color: #c1121f;
            border-color: #c1121f;
            transform: translateY(-2px);
        }

        .required-field::after {
            content: " *";
            color: var(--primary);
        }

        .signup-links {
            text-align: center;
            margin-top: 20px;
        }

        .signup-links a {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.3s;
        }

        .signup-links a:hover {
            color: var(--secondary);
            text-decoration: underline;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .password-container {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 42px;
            cursor: pointer;
            color: #777;
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

        #imagePreview {
            display: block;
            max-width: 150px;
            height: auto;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
    </style>
</head>

<body>

    <!-- Hero Banner -->
    <section class="hero-banner">
        <div class="container">
            <h1>Register as Blood Recipient</h1>
            <p class="lead">Create your account to find life-saving blood donors when you need them</p>
        </div>
    </section>

    <!-- Breadcrumb -->
    <div class="breadcrumb-container">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index-recipient.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Sign Up</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Signup Form Section -->
    <section class="signup-section">
        <div class="container">
            <div class="signup-card">
                <h2 class="section-title">Create Your Recipient Account</h2>

                <?php
                // Display error messages if any
                if (isset($_POST['submit'])) {
                    if ($query->rowCount() > 0) {
                        echo '<div class="alert alert-danger">Email address already exists. Please try a different email.</div>';
                    } elseif (!$lastInsertId) {
                        echo '<div class="alert alert-danger">Something went wrong. Please try again.</div>';
                    }
                }
                ?>

                <form action="#" method="post" name="signup" enctype="multipart/form-data"
                    onsubmit="return checkpass();">
                    <div class="row">
                        <div class="form-group">
                            <label for="profileImage" class="form-label">Profile Image</label>
                            <input type="file" class="form-control" name="profileImage" id="profileImage"
                                accept="image/*">
                            <small class="text-muted">(Optional) Max 2MB. JPG, PNG, or GIF only.</small>
                        </div>

                        <div class="col-md-6">
                            <label for="fullname" class="form-label required-field">Full Name</label>
                            <input type="text" class="form-control" name="fullname" id="fullname"
                                placeholder="Enter your full name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="mobileno" class="form-label required-field">Mobile Number</label>
                            <input type="tel" class="form-control" name="mobileno" id="mobileno"
                                placeholder="Enter 10-digit mobile number" maxlength="10" pattern="[0-9]+" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="emailid" class="form-label required-field">Email Address</label>
                            <input type="email" name="emailid" class="form-control" placeholder="Enter your email"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label for="age" class="form-label required-field">Age</label>
                            <input type="number" class="form-control" name="age" id="age" placeholder="Enter your age"
                                min="1" max="100" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="gender" class="form-label required-field">Gender</label>
                            <select name="gender" class="form-select" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="bloodgroup" class="form-label required-field">Required Blood Group</label>
                            <select name="bloodgroup" class="form-select" required>
                                <option value="">Select Blood Group</option>
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
                        <label for="address" class="form-label required-field">Location</label>
                        <p class="text-muted">Click on the map to set your location for finding nearby donors</p>
                        <div id="map" style="height: 400px;"></div>

                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">

                        <script>
                            var map = L.map('map').setView([20.5937, 78.9629], 5); // Centered on India

                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '© OpenStreetMap contributors'
                            }).addTo(map);

                            var marker;

                            map.on('click', function (e) {
                                var lat = e.latlng.lat;
                                var lng = e.latlng.lng;

                                // Set values in hidden inputs
                                document.getElementById('latitude').value = lat;
                                document.getElementById('longitude').value = lng;

                                // Show marker
                                if (marker) {
                                    marker.setLatLng(e.latlng);
                                } else {
                                    marker = L.marker(e.latlng).addTo(map);
                                }
                            });
                        </script>

                    </div>

                    <div class="form-group">
                        <label for="message" class="form-label">Medical Details</label>
                        <textarea class="form-control" name="message"
                            placeholder="Any medical conditions or special requirements"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label required-field">Password</label>
                        <div class="password-container">
                            <input type="password" class="form-control" name="password" id="password"
                                placeholder="Create a strong password" required>
                            <span class="password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </span>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" name="submit" class="btn btn-register text-white">
                            <i class="fas fa-user-plus me-2"></i> Register as Recipient
                        </button>
                    </div>
                </form>

                <div class="signup-links">
                    <p class="mb-0">
                        Already have an account?
                        <a href="login-recipient.php">Sign in now</a>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery-3.6.0.min.js"></script>
    <script>
        // Password toggle function
        function togglePassword() {
            var passwordInput = document.getElementById('password');
            var toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Password validation function
        function checkpass() {
            var password = document.getElementById('password').value;

            // Add any additional password validation here if needed
            // For example: minimum length, complexity requirements

            return true; // Submit the form
        }
        
        // Image preview
        document.getElementById('profileImage').addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    // Create a preview if you want (optional)
                    const preview = document.createElement('img');
                    preview.src = e.target.result;
                    preview.style.maxWidth = '150px';
                    preview.style.marginTop = '10px';

                    // Remove previous preview if exists
                    const oldPreview = document.getElementById('imagePreview');
                    if (oldPreview) {
                        oldPreview.remove();
                    }

                    preview.id = 'imagePreview';
                    event.target.parentNode.appendChild(preview);
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>