<?php
//TODO: connect close in sign_up
//TODO: htmlspecialchars
//TODO: check existed username + email
//1. Forum page
//2. Session + count active user
//3. Setup the email to my mail for every user register

include_once('connection.php');
include_once('common_function.php');
?>

<html>
<head>
    <title>Sign up</title>
</head>

<body>
<center><strong><a href='index.php'>Home</a></strong>
<form method="post">
<table cellpadding="10">
    <tr style="background:lightblue;">
        <td width="45%">Info</td>
        <td>Value</td>
    </tr>
    <tr>
        <td>Email</td>
        <td><input type="email" name="txt_email"/></td>
    </tr>
    <tr>
        <td>Username</td>
        <td><input type="text" name="txt_username"></td>
    </tr>
    <tr>
        <td>Password</td>
        <td><input type="Password" name="txt_pass"/></td>
    </tr>
    <tr>
        <td>Password Confirmation</td>
        <td><input type="Password" name="txt_retype_pass"/></td>
    </tr>
    <tr>
        <td></td>
        <td><input type="submit" value="Register" name="btn_submit"/></td>
    </tr>
    <tr>
        <td></td>
        <td>Already has an account? <a href="sign_in.php">Sign in</a></td>
    </tr>
</table>
</form>

<?php
if (isset($_POST['btn_submit'])) {
        //user press "register" button

    $email = $_POST['txt_email'];
    $username = $_POST['txt_username'];
    $pass = $_POST['txt_pass'];
    $retyped_pass = $_POST['txt_retype_pass'];

    //check valid input for email, username, password, password confirmation
    if (!empty($username) && !empty($email) && (!empty($pass)) && (!empty($retyped_pass))) {

        //password must be greater than 5
        if (strlen($pass) > 5) {

            if ($retyped_pass == $pass) {
                    //password matching
                $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

                $sql = "INSERT INTO user (email, username, password) VALUES (:email, :username, :hashed_pass)";
                $statement = $pdo->prepare($sql);

                $statement->bindParam(':email', $email, PDO::PARAM_STR);
                $statement->bindParam(':username', $username, PDO::PARAM_STR);
                $statement->bindParam(':hashed_pass', $hashed_pass, PDO::PARAM_STR);

                if ($statement->execute()) {
                    echo "Account created successfully. Please <a href='sign_in.php'>sign in</a> to continue";
                } else {
                    echo "Error: " . $sql . "<br>" . $statement->errorInfo()[2];
                }

            } else {
                echo 'Please check the password confirmation';
            }
        } else {
            echo 'Password must be greater than 5';
        }
    } else {
        echo 'Please fill in all the fields';
    }
}
    //send email to confirm
    //$result = mail("SangDT12@fpt.edu.vn","My subject 111", 'Mail content here 222');
    //var_dump($result);

?>
</center>
</body>

</html>