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

    try {
        //Server settings
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   //Enable SMTP authentication
        $mail->Username = 'nhpd7554@gmail.com';                     //SMTP username
        $mail->Password = ' ';                               //SMTP password - deleted for safety
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
<div class="container my-3" style="width: 50%">
    <h3>Contact Form</h3>
    <form method="post">
        <div class="mb-3">
            <label for="inputFirstName" class="form-label">First Name</label>
            <input type="text" name="txt_fname" id="inputFirstName" class="form-control">
        </div>
        <div class="mb-3">
            <label for="inputLastName" class="form-label">Last Name</label>
            <input type="text" name="txt_lname" id="inputLastName" class="form-control">
        </div>
        <div class="mb-3">
            <label for="inputMessage" class="form-label">Message</label>
            <textarea type="text" name="textarea_message" id="inputMessage" class="form-control" rows="5"></textarea>
        </div>
        <div class="mb-3">
            <input class="btn btn-primary" type="submit" value="Send mail" name="btn_submit"/>
        </div>
    </form>
</div>