<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (isset($_POST['send'])) {
    $uid = $_SESSION['bbdmsdid']; // same session variable for login id
    $cid = $_GET['cid']; // donor id

    // ✅ Fetch recipient details (from tblrecipients, not tblblooddonars)
    $select = "SELECT FullName, EmailId, MobileNumber 
               FROM tblrecipients 
               WHERE id = :uid";
    $qry = $dbh->prepare($select);
    $qry->bindParam(':uid', $uid, PDO::PARAM_STR);
    $qry->execute();
    $recipient = $qry->fetch(PDO::FETCH_OBJ);

    // ✅ Fetch donor’s available blood units
    $donorSql = "SELECT BloodUnits 
                 FROM tblblooddonars 
                 WHERE id = :cid";
    $donorQry = $dbh->prepare($donorSql);
    $donorQry->bindParam(':cid', $cid, PDO::PARAM_STR);
    $donorQry->execute();
    $donorData = $donorQry->fetch(PDO::FETCH_OBJ);

    if ($donorData) {
        $bunits = (int) $donorData->BloodUnits;
    }

    if ($recipient) {
        $name = $recipient->FullName;
        $email = $recipient->EmailId;
        $contactno = $recipient->MobileNumber;
    }

    $requested_bunits = $_POST['bunits'];

    // ✅ Check max allowed units
    $max_allowed = $bunits - 10;
    if ($requested_bunits > $max_allowed) {
        echo "<script>alert('You can only request up to " . $max_allowed . " units of blood.');</script>";
    } else {
        $brf = $_POST['brf'];
        $message = $_POST['message'];

        // ✅ Insert recipient info + donor id into tblbloodrequirer
        $sql = "INSERT INTO tblbloodrequirer
                (BloodDonorID, name, EmailId, ContactNumber, BloodUnits, BloodRequirefor, Message) 
                VALUES (:cid, :name, :email, :contactno, :bunits, :brf, :message)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':cid', $cid, PDO::PARAM_STR);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':contactno', $contactno, PDO::PARAM_STR);
        $query->bindParam(':bunits', $requested_bunits, PDO::PARAM_STR);
        $query->bindParam(':brf', $brf, PDO::PARAM_STR);
        $query->bindParam(':message', $message, PDO::PARAM_STR);
        $query->execute();

        if ($dbh->lastInsertId()) {
            echo '<script>alert("Request has been sent. We will contact you shortly.")</script>';
        } else {
            echo "<script>alert('Something went wrong. Please try again.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Blood | Life Savior</title>
    <meta name="description" content="Submit your blood request to our donors">

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

        .request-section {
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

        .request-form {
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            max-width: 800px;
            margin: 0 auto;
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
            min-height: 150px;
            resize: none;
        }

        .btn-submit {
            background-color: var(--primary);
            border-color: var(--primary);
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s;
            width: 100%;
        }

        .btn-submit:hover {
            background-color: #c1121f;
            border-color: #c1121f;
            transform: translateY(-2px);
        }

        .required-field::after {
            content: " *";
            color: var(--primary);
        }

        @media (max-width: 768px) {
            .request-form {
                padding: 30px 20px;
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
            <h1>Request Blood Donation</h1>
            <p class="lead">Fill out the form below to contact a potential donor</p>
        </div>
    </section>

    <!-- Breadcrumb -->
    <div class="breadcrumb-container">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index-recipient.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Request Blood</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Request Form Section -->
    <section class="request-section">
        <div class="container">
            <div class="request-form">
                <h2 class="section-title">Blood Request Form</h2>
                <form action="#" method="post">

                    <div class="row">
                        <label for="bunits" class="form-label required-field">Needed Blood Units</label>
                        <input type="number" class="form-control" name="bunits" style="margin-left: 10px; width: 720px;"
                            required>
                    </div>

                    <div class="row">
                        <div>
                            <label for="brf" class="form-label required-field">Blood Required For</label>
                            <select class="form-select" id="brf" required name="brf">
                                <option value="">Select relationship</option>
                                <option value="Father">Father</option>
                                <option value="Mother">Mother</option>
                                <option value="Brother">Brother</option>
                                <option value="Sister">Sister</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="form-label required-field">Reason for Blood Requirement</label>
                        <textarea class="form-control" id="message" name="message"
                            placeholder="Please provide details about your blood requirement" required></textarea>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" name="send" class="btn btn-submit text-white">
                            <i class="fas fa-paper-plane me-2"></i> Submit Request
                        </button>
                    </div>
                </form>
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
                        <li><a href="index-recipient.php">Home</a></li>
                        <li><a href="donor-list.php">Find Donors</a></li>
                        <?php if (!isset($_SESSION['bbdmsdid'])) {
                            ?>
                            <li><a href="sign-up.php">Become Donor</a></li>
                        <?php } ?>
                        <li><a href="about-recipient.php">About</a></li>
                        <li><a href="contact-recipient.php">Contact</a></li>
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