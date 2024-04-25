<?php
//TODO: create session
//TODO: repair to display line 59
//TODO: htmlspecialchars

session_start();
include_once('connection.php');
include_once('common_function.php');
?>

<html>
<head>
    <title>Sign in</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
<?php //header
include('header.php');
?>

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
        $sql = 'SELECT password, username, userID, user_roleID FROM user WHERE email = :email';

        $statement = $pdo->prepare($sql);
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->execute();

        if ($statement->rowCount() > 0) {
            $row = $statement->fetch();
            $hashed_pass = $row['password'];
            $username = $row['username'];
            $userID = $row['userID'];
            $user_roleID = $row['user_roleID'];

            if (password_verify($pass,$hashed_pass)) {
                echo "Successfully signed in as <strong>$email</strong>. Click <a href='index.php'>here</a> to the homepage";

                // Create sessions so we know the user is logged in, they basically act like cookies but remember the data on the server.
                //session_regenerate_id();
                //$_SESSION['loggedin'] = true;
                $_SESSION['email'] = $email;
                $_SESSION['username'] = $username;
                $_SESSION['userID'] = $userID;
                $_SESSION['user_roleID'] = $user_roleID;
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</body>
</html>