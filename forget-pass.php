<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    //Server setting
    $mail->isSMTP(); //Send using SMTP
    $mail->Host = 'smtp.gmail.com'; //Set the SMTp server to send through
    $mail->SMTPAuth = true; //Enable SMTP authentication
    $mail->Username = 'akoreanup@gmail.com'; //SMTP username
    $mail->Password = "bajw tcpv vuhc eeuj"; //SMTP password
    // gmail password :: 1qazxsw2
    $mail->Port = 465; //TCP port to connect to; use 587 if you have set 'SMTPSecure = PHPMailer::ENCRYPTION_START
    $mail->SMTPSecure = "ssl";

    //Recipients
    $mail->setFrom('akoreanup@gmail.com', 'Admin KoreanUp');
    $mail->addAddress('prakaidao1128@gmail.com', 'Tes Send Email'); //add a recipient

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'Tangkwa Send New'; //Set email format to HTML
    $mail->Body = 'This is my message <b>in bold!</b>';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>