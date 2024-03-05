<?php
session_start();
include_once('connection.php');
include_once('common_function.php');
if (@$_SESSION['email']) {
?>
<html lang="en">
<head>
    <title>Forum</title>
</head>
<body>
<a href="index.php">Home</a> | <a href="profile.php">Profile</a>
<?php

?>

</body>
</html>
<?php
if (@$_GET['action']=='sign_out') {
    session_destroy();
    header('location: index.php');
}
?>
<?php
}
