<?php
//check user
if (!isset($_SESSION['email'])) {
    header('location: index.php');
    exit();
}

if (isset($_POST['btn_submit'])) {
    $to = "ndat7554@gmail.com";
    $email = $_SESSION['email'];
    $first_name = $_POST['txt_fname'];
    $last_name = $_POST['txt_lname'];
    $subject = "Contact Form";
    $message = "Name: " . $first_name . " " . $last_name . "\nMessage: " . "\n\n" . $_POST['textarea_message'];

    if (mail($to, $subject, $message)) {
        echo "Mail sent successfully";
    } else {
        echo "Error";
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