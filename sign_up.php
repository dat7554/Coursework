<?php
//code adapted from: https://developers.google.com/recaptcha/docs/display

//TODO: connect close in sign_up

include_once('connection.php');
include_once('common_function.php');
?>

<html>
<head>
    <title>Sign up</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
<?php //header
include('header.php');
?>

<div class="container mb-3" style="width: 28%; padding-top: 35px">
    <h1>Sign up</h1>
    <form method="post">
        <div class="mb-3">
            <label for="inputEmail" class="form-label">Email</label>
            <input type="text" name="txt_email" id="inputEmail" class="form-control">
        </div>
        <div class="mb-3">
            <label for="inputUsername" class="form-label">Username</label>
            <input type="text" name="txt_username" id="inputUsername" class="form-control">
        </div>
        <div class="mb-3">
            <label for="inputPass" class="form-label">Password</label>
            <input type="password" name="txt_pass" id="inputPass" class="form-control">
        </div>
        <div class="mb-3">
            <label for="inputConPass" class="form-label">Password Confirmation</label>
            <input type="password" name="txt_retype_pass" id="inputConPass" class="form-control">
        </div>
        <div class="mb-3">
            <label for="captcha" class="form-label">Captcha</label>
            <div class="g-recaptcha" data-sitekey="6LdBMsApAAAAABk6TFZvZBVqqLXWk2oPNB9dhQmp"></div>
        </div>
        <div class="mb-3">
            <p>By signing up, you agree our <a href="#">Terms</a> and <a href="#">Data Policy</a>.</p>
        </div>
        <div class="mb-3">
            <input class="btn btn-primary" type="submit" value="Register" name="btn_submit"/>
        </div>
        <div class="mb-3">
            <p>Already have an account? <a href="sign_in.php">Sign in</a></p>
        </div>
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

        if (empty($_POST['g-recaptcha-response'])) {
            echo "<p style='color: red'>Please solve reCAPTCHA</p>";
            exit();
        }

        $privatekey = "6LdBMsApAAAAACNif4mU4A3NgX-ZveRn1aM6jH_K";
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $privatekey . "&response=" . $_POST['g-recaptcha-response']);
        $data = json_decode($response);

        if (!$data->success) {
            echo "<p style='color: red'>Please try again</p>";
            exit();
        }

        $sql = "SELECT email, username FROM user WHERE email = :email OR username = :username";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->bindParam(':username', $username, PDO::PARAM_STR);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if ($row > 0) {
            if ($row['username'] == $username) {
                echo "<p style='color: red'>Username is already in used</p>";
            } elseif ($row['email'] == $email) {
                echo "<p style='color: red'>Email is already in used</p>";
            }
            exit();
        }

        //password must be greater than 5
        if (strlen($pass) > 5) {

            if ($retyped_pass == $pass) {
                    //password matching
                $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
                $user_roleID = 2;

                $sql = "INSERT INTO user (email, username, password, user_roleID) VALUES (:email, :username, :hashed_pass, :user_roleID)";
                $statement = $pdo->prepare($sql);
                $statement->bindParam(':email', $email, PDO::PARAM_STR);
                $statement->bindParam(':username', $username, PDO::PARAM_STR);
                $statement->bindParam(':hashed_pass', $hashed_pass, PDO::PARAM_STR);
                $statement->bindParam(':user_roleID', $user_roleID, PDO::PARAM_INT);

                if ($statement->execute()) {
                    echo "Account created successfully. Please <a href='sign_in.php'>sign in</a> to continue";
                } else {
                    echo "Error: " . $sql . "<br>" . $statement->errorInfo()[2];
                }

            } else {
                echo '<p style="color: red">Please check the password confirmation</p>';
            }
        } else {
            echo '<p style="color: red">Password must be greater than 5</p>';
        }
    } else {
        echo '<p style="color: red">Please fill in all the fields</p>';
    }
}
?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</body>
</html>