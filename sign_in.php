<?php
session_start();
include_once('connection.php');
include_once('common_function.php');
?>

<html>
<head>
    <title>Sign in</title>
</head>

<body>
<form method="post">
    <table cellpadding="10">
        <tr style="background:lightblue;">
            <td width="20%">Info</td>
            <td>Value</td>
        </tr>
        <tr>
            <td>Username</td>
            <td><input placeholder="Enter username or email" type="text" name="txt_username"/></td>
        </tr>
        <tr>
            <td>Password</td>
            <td><input type="Password" name="txt_pass"/></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="Sign in" name="btn_submit"/></td>
        </tr>
    </table>
</form>
<?php
if (isset($_POST['btn_submit'])) {
    //user press "register" button

    $username = $_POST['txt_username'];
    $pass = $_POST['txt_pass'];

    if (isset($username) && isset($pass)) {
        $sql = 'SELECT password FROM user 
                WHERE username = :username OR email = :username';

        $statement = $pdo->prepare($sql);
        $statement->bindParam(':username', $username, PDO::PARAM_STR);
        $statement->execute();

        if ($statement->rowCount() > 0) {
            $hashed_pass = $statement->fetch()['password'];
            if (password_verify($pass,$hashed_pass)) {
                echo "Successfully signed in as $username. Click <a href=''>here</a> to the homepage"; //TODO: link to homepage

                //TODO: create session and bind username and email inputs
                // Create sessions so we know the user is logged in, they basically act like cookies but remember the data on the server.
                //session_regenerate_id();
                //$_SESSION['loggedin'] = true;
                //$_SESSION['username'] = $username;
                //$_SESSION['emailuser'] = $userName;
                //header('location: /menu.php');
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
</body>
</html>