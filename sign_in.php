<?php
//TODO: create session
//TODO: repair to display line 59

session_start();
include_once('connection.php');
include_once('common_function.php');
?>

<html>
<head>
    <title>Sign in</title>
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
            <td><input type="text" name="txt_email"/></td>
        </tr>
        <tr>
            <td>Password</td>
            <td><input type="Password" name="txt_pass"/></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="Sign in" name="btn_submit"/></td>
        </tr>
        <tr>
            <td></td>
            <td>Don't have an account? <a href="sign_up.php">Sign up</a></td>
        </tr>
    </table>
</form>
<?php
if (isset($_POST['btn_submit'])) {
    //user press "register" button

    $email = $_POST['txt_email'];
    $pass = $_POST['txt_pass'];

    if (isset($email) && isset($pass)) {
        $sql = 'SELECT password FROM user WHERE email = :email';

        $statement = $pdo->prepare($sql);
        $statement->bindParam(':email', $email, PDO::PARAM_STR);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            $hashed_pass = $statement->fetch()['password'];
            if (password_verify($pass,$hashed_pass)) {
                echo "Successfully signed in as <strong>$email</strong>. Click <a href='index.php'>here</a> to the homepage";

                // Create sessions so we know the user is logged in, they basically act like cookies but remember the data on the server.
                //session_regenerate_id();
                //$_SESSION['loggedin'] = true;
                $_SESSION['email'] = $email;
                header('location: index.php');
            } else {
                echo 'Incorrect password';
            }
        } else {
            echo 'Error: ' . $sql . '<br>' . $statement->errorInfo()[2];
            echo 'Incorrect username or email';
        }
    } else {
        echo 'Please fill in all the fields';
    }
}
?>
</center>
</body>
</html>