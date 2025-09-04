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
    <title>About Us | Life Savior</title>
    <meta name="description" content="Learn about our mission to connect blood donors with recipients and save lives">

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

        .about-section {
            padding: 80px 0;
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

        .mission-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            transition: transform 0.3s;
            border: none;
        }

        .mission-card:hover {
            transform: translateY(-5px);
        }

        .mission-icon {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 20px;
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

        .team-section {
            padding: 80px 0;
            background-color: var(--light);
        }

        .team-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            height: 400px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            margin-bottom: 30px;
        }

        .team-card:hover {
            transform: translateY(-10px);
        }

        .team-img {
            height: 250px;
            object-fit: cover;
            width: 100%;
        }

        .team-social {
            background-color: var(--primary);
            padding: 15px;
            text-align: center;
        }

        .team-social a {
            color: white;
            margin: 0 10px;
            font-size: 1.2rem;
            transition: all 0.3s;
        }

        .team-social a:hover {
            color: var(--secondary);
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
    <?php include('includes/header-recipient.php'); ?>

    <!-- Hero Banner -->
    <section class="hero-banner">
        <div class="container">
            <h1>About Our Blood Bank</h1>
            <p class="lead">Learn about our mission to save lives through blood donation</p>
        </div>
    </section>

    <!-- Breadcrumb -->
    <div class="breadcrumb-container">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">About Us</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- About Content -->
    <section class="about-section">
        <div class="container" style="text-align: justify;">
            <?php
            $pagetype = "aboutus";
            $sql = "SELECT type,detail,PageName from tblpages where type=:pagetype";
            $query = $dbh->prepare($sql);
            $query->bindParam(':pagetype', $pagetype, PDO::PARAM_STR);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ);

            if ($query->rowCount() > 0) {
                foreach ($results as $result) {
                    ?>
                    <div class="row justify-content-around">
                        <div class="col-lg-10 text-justify">
                            <h2 class="section-title"><?php echo htmlentities($result->PageName); ?></h2>
                            <div class="about-content text-justify">
                                <?php echo $result->detail; ?>
                            </div>
                        </div>
                    </div>
                <?php }
            } ?>
        </div>
    </section>

    <!-- Our Mission -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title">Our Mission & Values</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="mission-card text-center">
                        <div class="mission-icon">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <h3>Save Lives</h3>
                        <p>Our primary mission is to facilitate life-saving blood donations and ensure timely
                            availability for patients in need.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="mission-card text-center">
                        <div class="mission-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>Build Community</h3>
                        <p>We connect donors, recipients, and medical professionals to create a supportive network of
                            life-savers.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="mission-card text-center">
                        <div class="mission-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3>Ensure Safety</h3>
                        <p>We maintain the highest standards of safety and quality in all our blood collection and
                            distribution processes.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number">10,000+</div>
                        <div class="stat-label">Donations</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number">5,000+</div>
                        <div class="stat-label">Lives Saved</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number">1,200+</div>
                        <div class="stat-label">Active Donors</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number">15+</div>
                        <div class="stat-label">Years Serving</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section">
        <div class="container">
            <h2 class="section-title">Our Dedicated Team</h2>
            <p class="text-center mb-5">Meet the professionals working tirelessly to make our mission possible</p>

            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="team-card">
                        <img src="images/testimonial1.png" class="team-img" alt="Medical Director">
                        <div class="p-4">
                            <h4>Dr. Sarah Johnson</h4>
                            <p class="text-muted">Medical Director</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="team-card">
                        <img src="images/testimonial2.jpg" class="team-img" alt="Donor Coordinator">
                        <div class="p-4">
                            <h4>Michael Chen</h4>
                            <p class="text-muted">Donor Coordinator</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="team-card">
                        <img src="images/testimonial3.jpg" class="team-img" alt="Lab Technician">
                        <div class="p-4">
                            <h4>David Martinez</h4>
                            <p class="text-muted">Lab Technician</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="team-card">
                        <img src="images/testimonial4.jpg" class="team-img" alt="Community Manager">
                        <div class="p-4">
                            <h4>Emily Wilson</h4>
                            <p class="text-muted">Community Manager</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php if (!isset($_SESSION['bbdmsdid'])) {
    ?>
    <!-- Call to Action -->
    <section class="py-5 bg-primary text-white">
        <div class="container text-center py-4">
            <h2 class="mb-4">Ready to Make a Difference?</h2>
            <p class="lead mb-4">Join our community of life-savers today</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="become-donor.php" class="btn btn-light btn-lg">Become a Donor</a>
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
                        <li><a href="index.php">Home</a></li>
                        <li><a href="donor-list.php">Find Donors</a></li>
                        <?php if (!isset($_SESSION['bbdmsdid'])) {
                        ?>
                        <li><a href="sign-up.php">Become Donor</a></li>
                        <?php } ?>
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
                <p class="mb-0">&copy; <?php echo date("Y"); ?> Life Savior. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery-3.6.0.min.js"></script>
</body>

</html>