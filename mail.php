<?php
//code adapted from: https://github.com/PHPMailer/PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

//check user
if (!isset($_SESSION['email'])) {
    exit();
}

if (isset($_POST['btn_submit'])) {
    $to = "ndat7554@gmail.com";
    $email = $_SESSION['email'];
    $first_name = $_POST['txt_fname'];
    $last_name = $_POST['txt_lname'];
    $subject = "Contact Form";
    $message = "Name: " . $first_name . " " . $last_name . "\nMessage: " . "\n\n" . $_POST['textarea_message'];

    if (empty($first_name) or empty($last_name) or empty($message)) {
        echo "Please fill in all required fields";
        exit();
    }

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   //Enable SMTP authentication
        $mail->Username = 'nhpd7554@gmail.com';                     //SMTP username
        $mail->Password = 'oruc bvyu fgxu znxr';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port = 587;                                    //TCP port to connect to

        //Recipients
        $mail->setFrom($email, $last_name);
        $mail->addAddress('ndat7554@gmail.com');
        $mail->addReplyTo($email, 'User contact');

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->AltBody = $message;

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>

<form method="post">
    <table cellpadding="10">
        <tr style="background:lightblue;">
            <td style='border: 1px solid black; padding: 20px;' width='400px' colspan='2'>Contact</td>
        </tr>
        <tr>
            <td>First Name:</td>
            <td><input type="text" name="txt_fname"></td>
        </tr>
        <tr>
            <td>Last Name</td>
            <td><input type="text" name="txt_lname"/></td>
        </tr>
        <tr>
            <td>Message</td>
            <td><textarea style="resize: none; width: 100%; height: 150px" name="textarea_message"></textarea></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="Send mail" name="btn_submit"></td>
        </tr>
</form>