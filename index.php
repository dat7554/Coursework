<?php
session_start();
include_once('connection.php');
include_once('common_function.php');
?>

<html lang="en">
<head>
    <title>Forum</title>
</head>
<body>

<?php
//header
if (@$_SESSION['email']) { //check session
    $email = $_SESSION['email'];
    $sql = "SELECT userID FROM user WHERE email = :email";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':email', $email, PDO::PARAM_STR);
    $statement->execute();
    $id = $statement->fetch()['userID'];

    echo "<center><a href='index.php'>Home</a> | <a href='profile.php?id=$id'>Profile</a>";
    echo "</center>";
    echo "<p>Welcome, <strong>" . @$_SESSION['email'] . "</strong> | <a href='index.php?action=sign_out'>Sign out</a></p>";
} else { //header public view
    echo "<center><a href='index.php'>Home</a> | <a href='sign_in.php'>Sign in</a> | <a href='sign_up.php'>Sign up</a></center>";
}
?>

</body>
</html>
<?php
if (@$_GET['action']=='sign_out') {
    session_destroy();
    header('location: index.php');
}
?>