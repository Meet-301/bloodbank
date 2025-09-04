<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/SMTP.php';
include('includes/config.php');

$id   = intval($_GET['id']);
$type = $_GET['type'];

// Fetch donor details from DB
$sql = "SELECT FullName, EmailId FROM tblblooddonars WHERE id = :id";
$query = $dbh->prepare($sql);
$query->bindParam(':id', $id, PDO::PARAM_STR);
$query->execute();
$donor = $query->fetch(PDO::FETCH_OBJ);
$status=0;

if ($donor) {
    // Assign variables from DB record
    $name  = $donor->FullName;
    $email = $donor->EmailId;

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'pujarameet301@gmail.com'; // your Gmail
        $mail->Password   = 'ovpzzsrvwpojxlky';        // Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('pujarameet301@gmail.com', 'Life Savior');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);

        // Decide email content based on $type
        if ($type === 'approve') {
            $mail->Subject = 'Life Savior: Donor Account Approved';
            $mail->Body    = "
                Hello <b>{$name}</b>,<br><br>
                Congratulations! Your donor account request has been <p style='color:green;'>approved</p> by the admin.<br>
                You can now log in and start helping those in need.<br><br>
                Thank you for considering us.<br>
                <b>– Life Savior Team</b>
            ";
            $status = 1;
        } elseif ($type === 'reject') {
            $mail->Subject = 'Life Savior: Donor Account Request Rejected';
            $mail->Body    = "
                Hello <b>{$name}</b>,<br><br>
                We regret to inform you that your donor account request has been <p style='color:green;'>rejected</p> by the admin.<br>
                Thank you for your interest and willingness to help.<br><br>
                <b>– Life Savior Team</b>
            ";
            echo "<script>alert('Donor rejected'); window.location.href='/admin/dashboard.php';</script>";
        } else {
            echo "<script>alert('Something went wrong')</script>";
        }

        // Send email
        if ($mail->send()) {
            if($status == 1) {
                echo "<script>alert('Donor appproved');</script>";
            } else {
                echo "<script>alert('Donor rejected');</script>";
            }
            header("Location: donor-approvals.php");
            exit();
        } else {
            echo "<script>alert('Mail sending failed'); window.history.back();</script>";
        }
    } catch (Exception $e) {
        echo "❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo "No donor record found.";
}