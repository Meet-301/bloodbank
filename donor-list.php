
<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Get filter values from request
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$bloodGroup = isset($_GET['bloodgroup']) ? $_GET['bloodgroup'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donor List | Life Savior</title>
    <meta name="description" content="View our list of available blood donors and find the perfect match for your needs">
    
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
        }
        
        h1, h2, h3, h4, h5, h6 {
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
        
        .donor-section {
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
        
        .donor-card {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 30px;
            border: none;
        }
        
        .donor-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
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
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }
        
        .donor-info-table {
            word-break: break-word;
            max-width: 250px;
            margin-bottom: 15px;
        }
        
        .donor-info-table th {
            width: 40%;
            font-weight: 600;
            color: var(--secondary);
        }
        
        .donor-info-table td {
            color: #666;
        }
        
        .btn-request {
            background-color: var(--primary);
            color: white;
            font-weight: 600;
            padding: 8px 20px;
            transition: all 0.3s;
        }
        
        .btn-request:hover {
            background-color: #c1121f;
            transform: translateY(-2px);
        }
        
        .search-container {
            background-color: var(--light);
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 40px;
        }
        
        @media (max-width: 768px) {
            .donor-card {
                margin-bottom: 20px;
            }
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
            color: rgba(255,255,255,0.7);
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
            background-color: rgba(255,255,255,0.1);
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
            border-top: 1px solid rgba(255,255,255,0.1);
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
    <?php include('includes/header.php');?>
    
    <!-- Hero Banner -->
    <section class="hero-banner">
        <div class="container">
            <h1>Our Blood Donors</h1>
            <p class="lead">Meet the heroes who help save lives every day</p>
        </div>
    </section>
    
    <!-- Breadcrumb -->
    <div class="breadcrumb-container">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Blood Donor List</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <!-- Donor List Section -->
    <section class="donor-section">
        <div class="container">
            <h2 class="section-title">Available Blood Donors</h2>
            
            <!-- Search Section -->
            <div class="search-container">
                <form method="get" action="">
                    <div class="row">
                        <div class="col-md-8 mb-3 mb-md-0">
                            <input type="text" class="form-control" name="search" placeholder="Search by name..." 
                                   value="<?php echo htmlspecialchars($searchTerm); ?>">
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" name="bloodgroup">
                                <option value="">Filter by Blood Group</option>
                                <?php 
                                $sql = "SELECT * from tblbloodgroup";
                                $query = $dbh->prepare($sql);
                                $query->execute();
                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                if($query->rowCount() > 0) {
                                    foreach($results as $result) {  
                                ?>  
                                <option value="<?php echo htmlentities($result->BloodGroup);?>"
                                    <?php if($bloodGroup == $result->BloodGroup) echo 'selected'; ?>>
                                <?php echo htmlentities($result->BloodGroup);?>
                                </option>
                                <?php } } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search me-2"></i>Search
                            </button>
                            <a href="donor-list.php" class="btn btn-outline-secondary">
                                <i class="fas fa-sync-alt me-2"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="row">
                <?php
                $status = 1;
                $sql = "SELECT * FROM tblblooddonars WHERE status=:status";
                $params = [':status' => $status];
                
                // Add search conditions if provided
                if (!empty($searchTerm)) {
                    $sql .= " AND (FullName LIKE :search)";
                    $params[':search'] = '%' . $searchTerm . '%';
                }
                
                // Add blood group filter if provided
                if (!empty($bloodGroup)) {
                    $sql .= " AND BloodGroup = :bloodgroup";
                    $params[':bloodgroup'] = $bloodGroup;
                }
                
                $query = $dbh->prepare($sql);
                
                // Bind all parameters
                foreach ($params as $key => &$val) {
                    $query->bindParam($key, $val);
                }
                
                $query->execute($params);
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                
                if($query->rowCount() > 0) {

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

                    $address = "Unknown Location";
                    if (!empty($results)) {
                        $lat = $results[0]->latitude;
                        $lng = $results[0]->longitude;
                        $address = getAddressFromCoordinates($lat, $lng);
                    }

                    foreach($results as $result) {
                        if (isset($_SESSION['bbdmsdid']) && $result->id == $_SESSION['bbdmsdid']) {
                            continue;
                        }
                        $address = getAddressFromCoordinates($result->latitude, $result->longitude);
                        ?>
                
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="donor-card">
                        
                        <div class="position-relative">
                        <img src="uploads/<?php echo trim($result->profile_image); ?>" class="card-img-top donor-img" alt="Blood Donor">
                            <div class="blood-group-badge"><?php echo htmlentities($result->BloodGroup);?></div>
                        </div>
                        <div class="p-4">
                            <h4 class="mb-3"><?php echo htmlentities($result->FullName);?></h4>
                            
                            <table class="donor-info-table">
                                <tbody>
                                    <tr>
                                        <th>Blood Units:</th>
                                        <td><?php echo htmlentities($result->BloodUnits);?></td>
                                    </tr>
                                    <tr>
                                        <th>Gender:</th>
                                        <td><?php echo htmlentities($result->Gender);?></td>
                                    </tr>
                                    <tr>
                                        <th>Age:</th>
                                        <td><?php echo htmlentities($result->Age);?></td>
                                    </tr>
                                    <tr>
                                        <th>Contact:</th>
                                        <td><?php echo htmlentities($result->MobileNumber);?></td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td><?php echo htmlentities($result->EmailId);?></td>
                                    </tr>
                                    <tr>
                                        <th>Location:</th>
                                        <td><?php echo htmlentities($address); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <div class="d-flex justify-content-between align-items-center text-white">
                                <?php if(isset($_SESSION['bbdmsdid'])) { ?>
                                    <a href="contact-blood.php?cid=<?php echo $result->id; ?>" class="btn btn-danger w-100">
                                        <i class="fas fa-hand-holding-medical me-2"></i>Request Blood
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } } else { ?>
                <div class="col-12 text-center py-5">
                    <div class="alert alert-info">
                        <h4>No donors found matching your criteria</h4>
                        <p class="mb-0">Please try different search terms or contact us for assistance.</p>
                    </div>
                </div>
                <?php } ?>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery-3.6.0.min.js"></script>
</body>
</html>