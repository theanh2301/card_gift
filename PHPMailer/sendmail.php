<?php
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    public function sendMail($title, $content, $addressMail)
    {

        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->CharSet = 'UTF-8';
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'manh.dt.02032003@gmail.com';
            $mail->Password   = 'enwq aicw prxj yvxb';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;


            $mail->setFrom('manh.dt.02032003@gmail.com', 'manh');
            $mail->addAddress($addressMail);

            $mail->isHTML(true);
            $mail->Subject = $title;
            $mail->Body    =  $content;
          

            $mail->send();
            echo 'Message has been sent' . $addressMail;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
