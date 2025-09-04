<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/SMTP.php';
include('includes/config.php');

if (!empty($_SESSION['signup_email']) && !empty($_SESSION['signup_name'])) {
    $email = $_SESSION['signup_email'];
    $name = $_SESSION['signup_name'];

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'pujarameet301@gmail.com';
        $mail->Password = 'ovpzzsrvwpojxlky'; // app password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('pujarameet301@gmail.com', 'Life Savior');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Life Savior: Update on New Donor Account Creation';
        $mail->Body = "Hello $name,<br><br>Your request for creation of your donor account has been submitted successfully.
                       <br><br>It's under admin approval. Once the admin approves your request, you can login into your account.<br><br>
                       <b>– Life Savior Team</b>";

        if ($mail->send()) {
            unset($_SESSION['signup_email'], $_SESSION['signup_name']);
            echo "<script>alert('New Donor Account Creation Requested'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Mail sending failed');</script>";
        }
    } catch (Exception $e) {
        echo "❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>