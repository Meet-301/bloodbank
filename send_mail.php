<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/SMTP.php';
include('includes/config.php'); // DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'pujarameet301@gmail.com';
        $mail->Password = 'ovpzzsrvwpojxlky';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('pujarameet301@gmail.com', 'Life Savior');

        if (isset($_POST['date']) && isset($_POST['time_slot'])) {
            // ✅ APPROVAL LOGIC
            $email = $_POST['patient_email'];
            $name = $_POST['patient_name'];
            $date = $_POST['date'];
            $time_slot = $_POST['time_slot'];
            $lat = $_POST['latitude'];
            $lng = $_POST['longitude'];
            $bunits = $_POST['blood_units'];
            $mapsLink = "https://www.google.com/maps/search/?api=1&query={$lat},{$lng}";

            $rawDate = $date;
            $formattedDate = date('j-n-Y', strtotime($rawDate));

            $mail->addAddress($email, $name);
            $mail->isHTML(true);
            $mail->Subject = 'Life Savior: Update on Your Blood Request';
            $mail->Body = "
                Hello $name,<br><br>
                Your blood request has been <p style='color:green;'>approved</p>.<br><br>

                <b>Scheduled Date:</b> $formattedDate<br>
                <b>Time Slot:</b> $time_slot<br>
                <b>Donor Location:</b> <a href='$mapsLink' target='_blank'>📍 View on Google Maps</a><br><br>

                Thank you for considering us.<br>
                <b>– Life Savior Team</b>
            ";
        } else {
            echo "<script>alert('Incomplete form submission');window.history.back();</script>";
            exit();
        }

        // Send email
        if ($mail->send()) {
            // Delete request from DB
            $delete = $dbh->prepare("DELETE FROM tblbloodrequirer WHERE EmailId = :email");
            $delete->bindParam(':email', $email, PDO::PARAM_STR);
            $delete->execute();

            $update = $dbh->prepare("
            UPDATE tblblooddonars 
            SET BloodUnits = BloodUnits - :bunits 
            WHERE EmailId = :email
            ");
            $update->bindParam(':bunits', $bunits, PDO::PARAM_INT);
            $update->bindParam(':email', $_SESSION['bbdmsdid'], PDO::PARAM_STR);
            $update->execute();

            echo "<script>alert('Blood request approved'); window.location.href='index.php';</script>";
            exit();
        } else {
            echo "<script>alert('Something went wrong');</script>";
        }
    } catch (Exception $e) {
        echo "❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>