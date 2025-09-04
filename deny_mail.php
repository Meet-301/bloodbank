<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/SMTP.php';
include('includes/config.php'); // DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['patient_email'];
    $name = $_POST['patient_name'];
    $message = $_POST['message'];

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
        $mail->addAddress($email);
        $mail->Subject = 'Life Savior: Update on Your Blood Request';
        $mail->Body    = "Hello $name,\n\nWe regret to inform you that your blood request has been denied.\n\nReason for denial from donor: $message";

        // Send email
        if ($mail->send()) {
            // Delete request from DB
            $delete = $dbh->prepare("DELETE FROM tblbloodrequirer WHERE EmailId = :email");
            $delete->bindParam(':email', $email, PDO::PARAM_STR);
            $delete->execute();

            echo "<script>alert('Blood request denied'); window.location.href='index.php';</script>";
            exit();
        } else {
            echo "<script>alert('Something went wrong');</script>";
        }
    } catch (Exception $e) {
        echo "❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>