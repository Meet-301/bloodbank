<?php
session_start();
error_reporting(0);
include('includes/config.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Life Savior | Save Lives, Donate Blood</title>
    <meta name="description" content="Join our life-saving community. Find blood donors or become one today.">

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

        .hero-section {
            background: linear-gradient(rgba(29, 53, 87, 0.8), rgba(29, 53, 87, 0.8)), url('images/hero-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 120px 0;
            text-align: center;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 30px;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #c1121f;
            border-color: #c1121f;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: var(--secondary);
            border-color: var(--secondary);
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-secondary:hover {
            background-color: #14213d;
            border-color: #14213d;
            transform: translateY(-2px);
        }

        .section-title {
            position: relative;
            margin-bottom: 50px;
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

        .donor-card {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 30px;
            border: none;
        }

        .donor-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .donor-img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }

        .blood-group-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: var(--primary);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        }

        .stats-section {
            background-color: var(--secondary);
            color: white;
            padding: 60px 0;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--primary);
        }

        .stat-label {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .blood-info-section {
            background-color: var(--light);
            padding: 80px 0;
        }

        .blood-type-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            height: 100%;
            transition: transform 0.3s;
        }

        .blood-type-card:hover {
            transform: translateY(-5px);
        }

        .blood-type-title {
            color: var(--secondary);
            margin-bottom: 20px;
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

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">Donate Blood, Save Lives</h1>
            <p class="hero-subtitle">Every drop counts. Join our community of life-savers today.</p>
            <div class="d-flex justify-content-center gap-3">
                <?php if (!isset($_SESSION['bbdmsdid'])) {
                    ?>
                    <a href="sign-up.php" class="btn btn-primary btn-lg" data-bs-toggle="modal"
                        data-bs-target="#becomeDonorModal">Become a Donor</a>
                <?php } ?>
                <a href="donor-list.php" class="btn btn-secondary btn-lg">Find a Donor</a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number">1,250+</div>
                        <div class="stat-label">Active Donors</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Lives Saved</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number">8</div>
                        <div class="stat-label">Blood Types</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Emergency Service</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Donors Section -->
    <section class="py-5">
        <div class="container py-5">
            <h2 class="section-title">Our Recent Donors</h2>
            <p class="text-center mb-5">These heroes recently donated blood and helped save lives.</p>

            <div class="row">
                <?php
                $status = 1;

                // Check if user is logged in
                if (!isset($_SESSION['bbdmsdid'])) {
                    echo '<div class="col-12 text-center"><p class="text-danger">Please login first.</p></div>';
                } else {
                    // Logged in user ID
                    $loggedInId = $_SESSION['bbdmsdid'];

                    // Fetch logged-in user's coordinates
                    $sql = "SELECT latitude, longitude FROM tblblooddonars WHERE id = :id LIMIT 1";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':id', $loggedInId, PDO::PARAM_INT);
                    $query->execute();
                    $userLoc = $query->fetch(PDO::FETCH_ASSOC);

                    if (!$userLoc || empty($userLoc['latitude']) || empty($userLoc['longitude'])) {
                        echo '<div class="col-12 text-center"><p class="text-warning">Your location is not set in the system.</p></div>';
                    } else {
                        $userLat = $userLoc['latitude'];
                        $userLng = $userLoc['longitude'];

                        // Fetch donors except the logged-in user
                        $sql = "SELECT * FROM tblblooddonars WHERE status = :status AND id != :id";
                        $query = $dbh->prepare($sql);
                        $query->bindParam(':status', $status, PDO::PARAM_INT);
                        $query->bindParam(':id', $loggedInId, PDO::PARAM_INT);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                        // Function to calculate distance
                        function calculateDistance($lat1, $lon1, $lat2, $lon2)
                        {
                            $earthRadius = 6371; // km
                            $dLat = deg2rad($lat2 - $lat1);
                            $dLon = deg2rad($lon2 - $lon1);
                            $a = sin($dLat / 2) * sin($dLat / 2) +
                                cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
                                sin($dLon / 2) * sin($dLon / 2);
                            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
                            return $earthRadius * $c;
                        }

                        function getAddressFromCoordinates($lat, $lng)
                        {
                            $apiKey = 'e63d227950ce4e1fb7abbed6718bd981';
                            $url = "https://api.opencagedata.com/geocode/v1/json?q=$lat+$lng&key=$apiKey";

                            $response = file_get_contents($url);
                            $json = json_decode($response, true);

                            if ($json && isset($json['results'][0]['formatted'])) {
                                return $json['results'][0]['formatted']; // full address
                            } else {
                                return "Unknown Location";
                            }
                        }

                        // Filter nearby donors
                        $nearbyDonors = [];
                        foreach ($results as $donor) {
                            $distance = calculateDistance($userLat, $userLng, $donor->latitude, $donor->longitude);
                            if ($distance <= 50) { // within 50 km
                                $nearbyDonors[] = $donor;
                            }
                        }

                        // Show results or message
                        if (empty($nearbyDonors)) {
                            echo '<div class="col-12 text-center"><p class="text-muted">No nearby donors found.</p></div>';
                        } else {
                            foreach ($nearbyDonors as $result) {
                                $address = getAddressFromCoordinates($result->latitude, $result->longitude);
                                ?>
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card donor-card">
                                        <div class="position-relative">
                                            <img src="uploads/<?php echo trim($result->profile_image); ?>"
                                                class="card-img-top donor-img" alt="Blood Donor">
                                            <div class="blood-group-badge"><?php echo htmlentities($result->BloodGroup); ?></div>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo htmlentities($result->FullName); ?></h5>
                                            <p class="card-text">
                                                <i class="fas fa-venus-mars me-2"></i><?php echo htmlentities($result->Gender); ?><br>
                                                <i class="fas fa-map-marker-alt me-2"></i><?php echo htmlentities($address); ?><br>
                                                <i class="fas fa-tint me-2"></i><?php echo htmlentities($result->BloodUnits); ?> Units
                                            </p>
                                            <a href="contact-blood.php?cid=<?php echo $result->id; ?>" class="btn btn-primary w-100">
                                                <i class="fas fa-hand-holding-medical me-2"></i>Request Blood
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                    }
                }
                ?>
            </div>

            <div class="text-center mt-4">
                <a href="donor-list.php" class="btn btn-outline-primary btn-lg">View All Donors</a>
            </div>
        </div>
    </section>

    <!-- Blood Info Section -->
    <section class="blood-info-section">
        <div class="container">
            <h2 class="section-title">Blood Types Information</h2>
            <p class="text-center mb-5">Understanding blood types is crucial for safe transfusions.</p>

            <div class="row mb-5">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="blood-type-card">
                        <h3 class="blood-type-title">Blood Group Types</h3>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-tint text-danger me-2"></i> A positive or A negative</li>
                            <li class="mb-2"><i class="fas fa-tint text-danger me-2"></i> B positive or B negative</li>
                            <li class="mb-2"><i class="fas fa-tint text-danger me-2"></i> O positive or O negative</li>
                            <li class="mb-2"><i class="fas fa-tint text-danger me-2"></i> AB positive or AB negative
                            </li>
                        </ul>
                        <p class="mt-3">A healthy diet helps ensure a successful blood donation and makes you feel
                            better!</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="blood-type-card">
                        <h3 class="blood-type-title">Universal Donors & Recipients</h3>
                        <p>The most common blood type is O, followed by type A.</p>
                        <div class="alert alert-info mt-3">
                            <h5><i class="fas fa-user-shield me-2"></i> Universal Donors</h5>
                            <p class="mb-0">Type O negative individuals are called "universal donors" since their blood
                                can be transfused into persons with any blood type.</p>
                        </div>
                        <div class="alert alert-success mt-3">
                            <h5><i class="fas fa-user-plus me-2"></i> Universal Recipients</h5>
                            <p class="mb-0">Those with type AB positive blood are "universal recipients" because they
                                can receive blood of any type.</p>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!isset($_SESSION['bbdmsdid'])) {
                ?>
                <div class="text-center">
                    <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#becomeDonorModal">
                        <i class="fas fa-heartbeat me-2"></i> Become a Donor Today
                    </button>
                </div>
            <?php } ?>
        </div>
    </section>

    <!-- Donation Process Section -->
    <section class="py-5">
        <div class="container py-5">
            <h2 class="section-title">The Donation Process</h2>
            <p class="text-center mb-5">It's simple, safe, and saves lives. Here's how it works:</p>

            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="text-center p-4">
                        <div class="icon-circle mb-3 mx-auto">
                            <i class="fas fa-user-check fa-2x"></i>
                        </div>
                        <h4>1. Registration</h4>
                        <p>Complete your donor registration and health history.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="text-center p-4">
                        <div class="icon-circle mb-3 mx-auto">
                            <i class="fas fa-stethoscope fa-2x"></i>
                        </div>
                        <h4>2. Health Check</h4>
                        <p>Quick health screening to ensure you can donate.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="text-center p-4">
                        <div class="icon-circle mb-3 mx-auto">
                            <i class="fas fa-tint fa-2x"></i>
                        </div>
                        <h4>3. Donation</h4>
                        <p>The actual donation takes about 8-10 minutes.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="text-center p-4">
                        <div class="icon-circle mb-3 mx-auto">
                            <i class="fas fa-cookie fa-2x"></i>
                        </div>
                        <h4>4. Refreshment</h4>
                        <p>Enjoy snacks and relax before resuming your day.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-5 bg-light">
        <div class="container py-5">
            <h2 class="section-title">Donor Stories</h2>
            <p class="text-center mb-5">Hear from people whose lives were changed by blood donation.</p>

            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="testimonial-card p-4 h-100">
                        <div class="d-flex mb-3">
                            <img src="images/testimonial1.png" class="rounded-circle me-3" width="60" height="60"
                                alt="Donor">
                            <div>
                                <h5 class="mb-0">Sarah Johnson</h5>
                                <p class="text-muted mb-0">Regular Donor</p>
                            </div>
                        </div>
                        <p class="mb-0">"I've been donating blood for 5 years now. Knowing that my blood has helped save
                            lives is the most rewarding feeling."</p>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="testimonial-card p-4 h-100">
                        <div class="d-flex mb-3">
                            <img src="images/testimonial2.jpg" class="rounded-circle me-3" width="60" height="60"
                                alt="Recipient">
                            <div>
                                <h5 class="mb-0">Michael Chen</h5>
                                <p class="text-muted mb-0">Blood Recipient</p>
                            </div>
                        </div>
                        <p class="mb-0">"After my accident, I needed 4 units of blood. I'm alive today because of
                            generous donors. Now I donate regularly to pay it forward."</p>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="testimonial-card p-4 h-100">
                        <div class="d-flex mb-3">
                            <img src="images/testimonial3.jpg" class="rounded-circle me-3" width="60" height="60"
                                alt="Donor">
                            <div>
                                <h5 class="mb-0">David Martinez</h5>
                                <p class="text-muted mb-0">First-time Donor</p>
                            </div>
                        </div>
                        <p class="mb-0">"I was nervous my first time, but the staff made it so easy. The whole process
                            was quick and painless. I'll definitely be back!"</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php if (!isset($_SESSION['bbdmsdid'])) {
        ?>
        <!-- Call to Action -->
        <section class="py-5 bg-primary text-white">
            <div class="container py-4 text-center">
                <h2 class="mb-4">Ready to Make a Difference?</h2>
                <p class="lead mb-4">One donation can save up to three lives. Join our community of life-savers today.</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="sign-up.php" class="btn btn-light btn-lg" data-bs-toggle="modal"
                        data-bs-target="#becomeDonorModal">Become a Donor</a>
                    <a href="contact.php" class="btn btn-outline-light btn-lg">Contact Us</a>
                </div>
            </div>
        </section>
    <?php } ?>

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

    <!-- Become Donor Modal -->
    <div class="modal fade" id="becomeDonorModal" tabindex="-1" aria-labelledby="becomeDonorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="becomeDonorModalLabel">Become a Blood Donor</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Thank you for your interest in becoming a blood donor! By registering, you'll join our network of
                        life-savers.</p>
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i> Eligibility Requirements:</h6>
                        <ul class="mb-0">
                            <li>Age 18-65 years</li>
                            <li>Weight at least 50kg</li>
                            <li>Good general health</li>
                        </ul>
                    </div>
                    <a href="sign-up.php" class="btn btn-primary w-100 mt-3">Register Now</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery-3.6.0.min.js"></script>
    <script>
        // Smooth scrolling for anchor links
        $(document).ready(function () {
            $("a").on('click', function (event) {
                if (this.hash !== "") {
                    event.preventDefault();
                    var hash = this.hash;
                    $('html, body').animate({
                        scrollTop: $(hash).offset().top
                    }, 800, function () {
                        window.location.hash = hash;
                    });
                }
            });

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
    <script>
        // Password validation
        function checkpass() {
            if (document.changepassword.newpassword.value != document.changepassword.confirmpassword.value) {
                alert('New Password and Confirm Password field does not match');
                document.changepassword.confirmpassword.focus();
                return false;
            }
            return true;
        }

        // Enable Bootstrap tooltips
        $(function () {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
</body>

</html>