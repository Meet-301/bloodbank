<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Function to calculate distance
function calculateDistance($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 6371; // km
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $earthRadius * $c;
}

// Function to get address from coordinates
function getAddressFromCoordinates($lat, $lng) {
    $apiKey = 'e63d227950ce4e1fb7abbed6718bd981';
    $url = "https://api.opencagedata.com/geocode/v1/json?q=$lat+$lng&key=$apiKey";
    
    $response = @file_get_contents($url);
    if ($response === false) {
        return "Location not available";
    }
    
    $json = json_decode($response, true);
    if ($json && isset($json['results'][0]['formatted'])) {
        return $json['results'][0]['formatted'];
    } else {
        return "Unknown Location";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Life Savior | Find Blood Donors</title>
    <meta name="description" content="Find blood donors near you to save lives">

    <!-- Favicon -->
    <link rel="icon" href="images/icon.jpg" type="image/jpeg">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">

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
            background-color: #f8f9fa;
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
            background: linear-gradient(rgba(29, 53, 87, 0.8), rgba(29, 53, 87, 0.8)), url('https://images.unsplash.com/photo-1517971129774-8a2b38fa128e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2000&q=80');
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
            background: white;
        }

        .donor-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .donor-img {
            height: 200px;
            object-fit: cover;
            width: 100%;
            background-color: #f1f1f1;
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

        .icon-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: var(--secondary);
        }

        .testimonial-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            padding: 25px;
            height: 100%;
        }

        .distance-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background-color: var(--secondary);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.9rem;
        }
        
        .donor-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            background-color: #e9ecef;
            color: #6c757d;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.2rem;
            }
            
            .stat-number {
                font-size: 2.2rem;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <?php include('includes/header-recipient.php'); ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">Find Blood Donors Near You</h1>
            <p class="hero-subtitle">Connect with life-savers in your area when you need it most</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <?php if (!isset($_SESSION['bbdmsrid'])): ?>
                    <!-- <a href="sign-up-recipient.php" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#becomeRecipientModal">
                        Register as Recipient
                    </a> -->
                <?php endif; ?>
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
            <h2 class="section-title">Available Donors Near You</h2>
            <p class="text-center mb-5">These heroes are ready to donate and save lives</p>

            <div class="row">
                <?php
                $status = 1;
                $loggedIn = false;
                $userLat = null;
                $userLng = null;
                $distanceWarning = false;

                // Check if user is logged in
                if (isset($_SESSION['bbdmsrid'])) {
                    $loggedIn = true;
                    // Logged in recipient ID
                    $loggedInId = $_SESSION['bbdmsrid'];

                    // Fetch logged-in recipient's coordinates
                    $sql = "SELECT latitude, longitude FROM tblrecipients WHERE id = :id LIMIT 1";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':id', $loggedInId, PDO::PARAM_INT);
                    $query->execute();
                    $userLoc = $query->fetch(PDO::FETCH_ASSOC);

                    if ($userLoc && !empty($userLoc['latitude']) && !empty($userLoc['longitude'])) {
                        $userLat = $userLoc['latitude'];
                        $userLng = $userLoc['longitude'];
                    } else {
                        $distanceWarning = true;
                        echo '<div class="col-12 text-center mb-4">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Your location is not set. Please update your profile to find nearby donors.
                                </div>
                              </div>';
                    }
                }

                // Fetch active donors from database
                $sql = "SELECT * FROM tblblooddonars WHERE status = :status";
                $query = $dbh->prepare($sql);
                $query->bindParam(':status', $status, PDO::PARAM_INT);
                $query->execute();
                $donors = $query->fetchAll(PDO::FETCH_ASSOC);

                // Filter nearby donors
                $nearbyDonors = [];
                $donorCount = 0;
                
                foreach ($donors as $donor) {
                    if ($donorCount >= 6) break;
                    
                    $distance = null;
                    $address = getAddressFromCoordinates($donor['latitude'], $donor['longitude']);
                    
                    if ($userLat && $userLng) {
                        $distance = calculateDistance($userLat, $userLng, $donor['latitude'], $donor['longitude']);
                        if ($distance > 50) continue; // Skip donors beyond 50km
                    }
                    
                    $nearbyDonors[] = [
                        'id' => $donor['id'],
                        'FullName' => $donor['FullName'],
                        'BloodGroup' => $donor['BloodGroup'],
                        'Gender' => $donor['Gender'],
                        'BloodUnits' => $donor['BloodUnits'],
                        'distance' => $distance,
                        'address' => $address,
                        'profile_image' => $donor['profile_image']
                    ];
                    
                    $donorCount++;
                }

                // Show results or message
                if (empty($nearbyDonors)) {
                    echo '<div class="col-12 text-center"><p class="text-muted">No nearby donors found.</p></div>';
                } else {
                    foreach ($nearbyDonors as $donor) {
                        $distanceText = $donor['distance'] ? number_format($donor['distance'], 1) . ' km away' : 'Nearby';
                        ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card donor-card">
                                <div class="position-relative">
                                    <?php if (!empty($donor['profile_image'])): ?>
                                        <img src="uploads/<?php echo htmlspecialchars($donor['profile_image']); ?>" 
                                             class="card-img-top donor-img" alt="Blood Donor">
                                    <?php else: ?>
                                        <div class="donor-placeholder">
                                            <i class="fas fa-user-circle fa-5x"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="blood-group-badge"><?php echo htmlspecialchars($donor['BloodGroup']); ?></div>
                                    <div class="distance-badge"><?php echo $distanceText; ?></div>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($donor['FullName']); ?></h5>
                                    <p class="card-text">
                                        <i class="fas fa-venus-mars me-2"></i><?php echo htmlspecialchars($donor['Gender']); ?><br>
                                        <i class="fas fa-map-marker-alt me-2"></i><?php echo htmlspecialchars($donor['address']); ?><br>
                                        <i class="fas fa-tint me-2"></i><?php echo htmlspecialchars($donor['BloodUnits']); ?> Units Available
                                    </p>
                                    <a href="contact-blood-recipient.php?cid=<?php echo $donor['id']; ?>" class="btn btn-primary w-100">
                                        <i class="fas fa-hand-holding-medical me-2"></i>Request Blood
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php
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
            <h2 class="section-title">Blood Compatibility</h2>
            <p class="text-center mb-5">Understanding blood compatibility is crucial for safe transfusions</p>

            <div class="row mb-5">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="blood-type-card">
                        <h3 class="blood-type-title">Who Can Donate to Whom</h3>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Recipient</th>
                                        <th>Can Receive From</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>O-</td>
                                        <td>O-</td>
                                    </tr>
                                    <tr>
                                        <td>O+</td>
                                        <td>O-, O+</td>
                                    </tr>
                                    <tr>
                                        <td>A-</td>
                                        <td>A-, O-</td>
                                    </tr>
                                    <tr>
                                        <td>A+</td>
                                        <td>A-, A+, O-, O+</td>
                                    </tr>
                                    <tr>
                                        <td>B-</td>
                                        <td>B-, O-</td>
                                    </tr>
                                    <tr>
                                        <td>B+</td>
                                        <td>B-, B+, O-, O+</td>
                                    </tr>
                                    <tr>
                                        <td>AB-</td>
                                        <td>AB-, A-, B-, O-</td>
                                    </tr>
                                    <tr>
                                        <td>AB+</td>
                                        <td>All Blood Types</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="blood-type-card">
                        <h3 class="blood-type-title">Universal Donors & Recipients</h3>
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
                        <div class="alert alert-warning mt-3">
                            <h5><i class="fas fa-exclamation-triangle me-2"></i> Important Note</h5>
                            <p class="mb-0">While universal donation/reception is possible in emergencies, it's always best to match exact blood types when possible.</p>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!isset($_SESSION['bbdmsrid'])): ?>
                <div class="text-center">
                    <!-- <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#becomeRecipientModal">
                        <i class="fas fa-hand-holding-heart me-2"></i> Register as Recipient
                    </button> -->
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Request Process Section -->
    <section class="py-5">
        <div class="container py-5">
            <h2 class="section-title">How to Request Blood</h2>
            <p class="text-center mb-5">Simple steps to get the blood you need quickly</p>

            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="text-center p-4">
                        <div class="icon-circle mb-3 mx-auto">
                            <i class="fas fa-search fa-2x"></i>
                        </div>
                        <h4>1. Find Donors</h4>
                        <p>Search for donors with matching blood type near you</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="text-center p-4">
                        <div class="icon-circle mb-3 mx-auto">
                            <i class="fas fa-envelope fa-2x"></i>
                        </div>
                        <h4>2. Send Request</h4>
                        <p>Contact donors through our secure messaging system</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="text-center p-4">
                        <div class="icon-circle mb-3 mx-auto">
                            <i class="fas fa-handshake fa-2x"></i>
                        </div>
                        <h4>3. Arrange Meeting</h4>
                        <p>Coordinate with donor at a convenient location</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="text-center p-4">
                        <div class="icon-circle mb-3 mx-auto">
                            <i class="fas fa-hospital fa-2x"></i>
                        </div>
                        <h4>4. Receive Blood</h4>
                        <p>Meet at a medical facility for safe transfusion</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-5 bg-light">
        <div class="container py-5">
            <h2 class="section-title">Recipient Stories</h2>
            <p class="text-center mb-5">Hear from people whose lives were saved by blood donation</p>

            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="testimonial-card p-4 h-100">
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-user fa-2x text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-0">Rajesh Sharma</h5>
                                <p class="text-muted mb-0">Accident Survivor</p>
                            </div>
                        </div>
                        <p class="mb-0">"After my accident, I needed blood urgently. Life Savior connected me with donors within hours. I'm alive today because of this platform."</p>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="testimonial-card p-4 h-100">
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-user fa-2x text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-0">Priya Patel</h5>
                                <p class="text-muted mb-0">Mother of Thalassemia Patient</p>
                            </div>
                        </div>
                        <p class="mb-0">"My son requires regular blood transfusions. This platform has been a lifesaver, helping us find donors consistently for his treatment."</p>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="testimonial-card p-4 h-100">
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-user fa-2x text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-0">Amit Kumar</h5>
                                <p class="text-muted mb-0">Surgery Patient</p>
                            </div>
                        </div>
                        <p class="mb-0">"When I needed rare AB- blood for my surgery, I thought it was impossible. Life Savior found 3 donors in my area within a day."</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php if (!isset($_SESSION['bbdmsrid'])): ?>
        <!-- Call to Action -->
        <section class="py-5 bg-primary text-white">
            <div class="container py-4 text-center">
                <h2 class="mb-4">Need Blood Urgently?</h2>
                <p class="lead mb-4">Register now to connect with donors who can save your life</p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <!-- <a href="sign-up-recipient.php" class="btn btn-light btn-lg" data-bs-toggle="modal" data-bs-target="#becomeRecipientModal">
                        Register as Recipient
                    </a> -->
                    <a href="contact.php" class="btn btn-outline-light btn-lg">Emergency Contact</a>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5><i class="fas fa-heartbeat me-2"></i> Life Savior</h5>
                    <p class="mt-3">Connecting those in need with life-saving blood donors when every second counts</p>
                    <div class="social-icons mt-3">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                    <h5>Quick Links</h5>
                    <ul class="mt-3">
                        <li><a href="donor-list.php">Find Donors</a></li>
                        <?php if (!isset($_SESSION['bbdmsrid'])): ?>
                            <!-- <li><a href="sign-up-recipient.php">Register</a></li> -->
                        <?php endif; ?>
                        <li><a href="about.php">About</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                    <h5>Resources</h5>
                    <ul class="mt-3">
                        <li><a href="blood-info.php">Blood Facts</a></li>
                        <li><a href="eligibility.php">Eligibility</a></li>
                        <li><a href="faq.php">FAQ</a></li>
                        <li><a href="blog.php">Blog</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h5>Contact Info</h5>
                    <ul class="mt-3">
                        <li><i class="fas fa-map-marker-alt me-2"></i> 123 Health St, Medical City</li>
                        <li><i class="fas fa-phone me-2"></i> +1 234 567 8900</li>
                        <li><i class="fas fa-envelope me-2"></i> help@lifesavior.com</li>
                        <li><i class="fas fa-clock me-2"></i> 24/7 Emergency Service</li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <p class="mb-0">&copy; <?php echo date("Y"); ?> Life Savior. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Become Recipient Modal -->
    <div class="modal fade" id="becomeRecipientModal" tabindex="-1" aria-labelledby="becomeRecipientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="becomeRecipientModalLabel">Register as Blood Recipient</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Registering as a recipient helps us connect you with donors when you need blood urgently.</p>
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i> How it works:</h6>
                        <ul class="mb-0">
                            <li>Create your recipient profile</li>
                            <li>Search for donors by blood type and location</li>
                            <li>Contact donors through our secure system</li>
                            <li>Receive notifications about available donors</li>
                        </ul>
                    </div>
                    <a href="sign-up-recipient.php" class="btn btn-primary w-100 mt-3">
                        <i class="fas fa-user-plus me-2"></i>Register Now
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Smooth scrolling for anchor links
        $(document).ready(function() {
            $("a").on('click', function(event) {
                if (this.hash !== "") {
                    event.preventDefault();
                    var hash = this.hash;
                    $('html, body').animate({
                        scrollTop: $(hash).offset().top
                    }, 800, function() {
                        window.location.hash = hash;
                    });
                }
            });
        });
    </script>
</body>
</html>