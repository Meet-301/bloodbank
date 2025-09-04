<?php
error_reporting(0);
session_start();
include('includes/config.php');
if (strlen($_SESSION['bbdmsdid'] == 0)) {
    header('location:logout.php');
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Life Savior | Donation Requests</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Favicon -->
        <link rel="icon" href="images/icon.jpg" type="image/jpeg">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="css/fontawesome-all.css">

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
                font-family: 'Roboto Condensed', sans-serif;
                background-color: #f5f5f5;
            }

            .page-header {
                background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('images/hero-bg.jpg');
                background-size: cover;
                background-position: center;
                color: white;
                padding: 80px 0;
                margin-bottom: 40px;
                text-align: center;
            }

            .page-title {
                position: relative;
                margin-bottom: 30px;
                font-weight: 700;
            }

            .page-title:after {
                content: "";
                display: block;
                width: 80px;
                height: 3px;
                background: var(--primary);
                margin: 15px auto 0;
            }

            .card {
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                margin-bottom: 30px;
                border: none;
            }

            .card-header {
                background-color: var(--primary);
                color: white;
                border-radius: 10px 10px 0 0 !important;
                padding: 15px 20px;
                font-weight: 600;
            }

            .table-responsive {
                border-radius: 10px;
                overflow: hidden;
            }

            .table {
                margin-bottom: 0;
            }

            .table thead th {
                background-color: var(--primary);
                color: white;
                border-bottom: none;
                font-weight: 500;
            }

            .table tbody tr:nth-child(even) {
                background-color: rgba(220, 53, 69, 0.05);
            }

            .breadcrumb {
                background-color: transparent;
                padding: 0;
                font-size: 14px;
            }

            .breadcrumb-item.active {
                color: var(--primary);
                font-weight: 500;
            }

            .no-records {
                text-align: center;
                padding: 20px;
                color: var(--primary);
                font-weight: bold;
            }

            .badge-request {
                background-color: var(--primary);
                color: white;
                padding: 5px 10px;
                border-radius: 20px;
                font-size: 12px;
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
        </style>
    </head>

    <body>
        <!-- Header -->
        <?php include('includes/header.php'); ?>

        <!-- Page Header -->
        <div class="page-header">
            <div class="container">
                <h1 class="page-title">Request Received</h1>
                <p class="lead">View all blood requests you've received from receipients</p>
            </div>
        </div>

        <!-- Page Content -->
        <div class="container py-4">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Request Received</li>
                </ol>
            </nav>

            <!-- Requests Card -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-inbox me-2"></i>Blood Requests</span>
                    <span class="badge-request"><?php
                    $uid = $_SESSION['bbdmsdid'];
                    $count_sql = "SELECT COUNT(*) FROM tblbloodrequirer WHERE BloodDonorID=:uid";
                    $count_query = $dbh->prepare($count_sql);
                    $count_query->bindParam(':uid', $uid, PDO::PARAM_STR);
                    $count_query->execute();
                    echo $count_query->fetchColumn();
                    ?> Requests</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Requester</th>
                                    <th>Contact</th>
                                    <th>Requested Blood Units</th>
                                    <th>Blood Needed For</th>
                                    <th>Message</th>
                                    <th>Request Date</th>
                                    <th>Approval</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $uid = $_SESSION['bbdmsdid'];
                                $sql = "SELECT 
                                tblbloodrequirer.BloodDonorID, 
                                tblbloodrequirer.name, 
                                tblbloodrequirer.EmailId, 
                                tblbloodrequirer.ContactNumber,
                                tblbloodrequirer.BloodUnits, 
                                tblbloodrequirer.BloodRequirefor, 
                                tblbloodrequirer.Message, 
                                tblbloodrequirer.ApplyDate, 
                                tblblooddonars.latitude, 
                                tblblooddonars.longitude, 
                                tblblooddonars.id as donid 
                                FROM tblbloodrequirer 
                                JOIN tblblooddonars 
                                    ON tblblooddonars.id = tblbloodrequirer.BloodDonorID 
                                WHERE tblbloodrequirer.BloodDonorID = :uid";

                                $query = $dbh->prepare($sql);
                                $query->bindParam(':uid', $uid, PDO::PARAM_STR);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                $cnt = 1;

                                if ($query->rowCount() > 0) {
                                    foreach ($results as $row) {
                                        $formattedDate = date('j-n-Y', strtotime($row->ApplyDate));
                                        ?>
                                        <tr>
                                            <td><?php echo htmlentities($cnt); ?></td>
                                            <td>
                                                <strong><?php echo htmlentities($row->name); ?></strong><br>
                                                <small class="text-muted"><?php echo htmlentities($row->EmailId); ?></small>
                                            </td>
                                            <td><?php echo htmlentities($row->ContactNumber); ?></td>
                                            <td><?php echo htmlentities($row->BloodUnits); ?></td>
                                            <td><?php echo htmlentities($row->BloodRequirefor); ?></td>
                                            <td><?php echo htmlentities($row->Message); ?></td>
                                            <td><?php echo htmlentities($formattedDate); ?></td>
                                            <td><button class="btn btn-success" data-bs-toggle="modal"
                                                    data-bs-target="#donationModal"
                                                    data-patient-email="<?php echo htmlentities($row->EmailId); ?>"
                                                    data-patient-name="<?php echo htmlentities($row->name); ?>"
                                                    data-lat="<?php echo htmlentities($row->latitude); ?>"
                                                    data-lng="<?php echo htmlentities($row->longitude); ?>"
                                                    data-blood-units="<?php echo htmlentities($row->BloodUnits) ?>">
                                                    Approve
                                                </button>
                                            </td>
                                            <td><button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#denyModal"
                                                    data-patient-email="<?php echo htmlentities($row->EmailId); ?>"
                                                    data-patient-name="<?php echo htmlentities($row->name); ?>">
                                                    Deny
                                                </button>
                                            </td>
                                        </tr>
                                        <?php
                                        $cnt = $cnt + 1;
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">No requests found</h5>
                                            <p class="text-muted">You haven't received any blood requests yet</p>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approval Modal -->
        <div class="modal fade" id="donationModal" tabindex="-1" aria-labelledby="donationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="send_mail.php" id="scheduleForm">
                    <input type="hidden" name="patient_email" id="patient_email">
                    <input type="hidden" name="patient_name" id="patient_name">
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">
                    <input type="hidden" name="blood_units" id="blood_units">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="donationModalLabel">Schedule Blood Donation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="date" class="form-label">Choose Date</label>
                                <input type="date" class="form-control" name="date" required>
                            </div>

                            <div class="mb-3">
                                <label for="time_slot" class="form-label">Choose Time Slot</label>
                                <select name="time_slot" class="form-control" required>
                                    <option value="">Select</option>
                                    <option>9:00 AM - 10:00 AM</option>
                                    <option>10:00 AM - 11:00 AM</option>
                                    <option>11:00 AM - 12:00 PM</option>
                                    <option>2:00 PM - 3:00 PM</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Send Confirmation</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Deny Modal -->
        <div class="modal fade" id="denyModal" tabindex="-1" aria-labelledby="denyModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="deny_mail.php" id="denyForm">
                    <input type="hidden" name="patient_email" id="deny_patient_email">
                    <input type="hidden" name="patient_name" id="deny_patient_name">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="denyModalLabel">Reason for Denial</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" required name="message" rows="3"
                                    placeholder="You may provide a reason"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">Send Denial</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


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

        <!-- Scripts -->
        <script src="js/jquery-2.2.3.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            var donationModal = document.getElementById('donationModal');
            donationModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;

                document.getElementById('patient_email').value = button.getAttribute('data-patient-email');
                document.getElementById('patient_name').value = button.getAttribute('data-patient-name');
                document.getElementById('latitude').value = button.getAttribute('data-lat');
                document.getElementById('longitude').value = button.getAttribute('data-lng');
                document.getElementById('blood_units').value = button.getAttribute('data-blood-units');
            });

            var denyModal = document.getElementById('denyModal');
            denyModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;

                document.getElementById('deny_patient_email').value = button.getAttribute('data-patient-email');
                document.getElementById('deny_patient_name').value = button.getAttribute('data-patient-name');
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

            // Initialize tooltips
            $(function () {
                $('[data-toggle="tooltip"]').tooltip();
            });
        </script>
    </body>

    </html>
<?php } ?>