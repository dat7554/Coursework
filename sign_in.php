<?php
session_start();
include_once('connection.php');
include_once('common_function.php');

//check user
if (isset($_SESSION['email'])) {
    header('location: index.php');
    exit();
}
?>

<html lang="en">
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

<div class="container mb-3" style="width: 25%; padding-top: 35px">
    <h1>Sign in</h1>
    <form method="post">
        <div class="mb-3">
            <label for="inputEmail" class="form-label">Email</label>
            <input type="text" name="txt_email" id="inputEmail" class="form-control">
        </div>
        <div class="mb-3">
            <label for="inputPass" class="form-label">Password</label>
            <input type="password" name="txt_pass" id="inputPass" class="form-control">
        </div>
        <div class="mb-3">
            <input class="btn btn-primary" type="submit" value="Sign in" name="btn_submit"/>
        </div>
        <div class="mb-3">
            <p>Don't have an account? <a href="sign_up.php">Sign up</a></p>
        </div>
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

                //create sessions to know the user is logged in
                $_SESSION['email'] = $email;
                $_SESSION['username'] = $username;
                $_SESSION['userID'] = $userID;
                $_SESSION['user_roleID'] = $user_roleID;
                header('location: index.php');
            } else {
                echo '<p style="color: red">Incorrect password</p>';
            }
        } else {
            echo '<p style="color: red">Incorrect username or email</p>';
        }
    }
}
?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</body>
</html>