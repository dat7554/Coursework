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
<a href="index.php">Home</a> | <a href="profile.php">Profile</a>
<?php
if (@$_SESSION['email']) {
    echo "<p>Welcome, <strong>" . @$_SESSION['email'] . "</strong> | <a href='index.php?action=sign_out'>Sign out</a></p>";
} else {
    echo "<a href='sign_in.php'>Sign in</a> | <a href='sign_up.php'>Sign up</a>";
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