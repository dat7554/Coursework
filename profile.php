<?php
//TODO: add personal information from database + add/change profile pic function + change pass + total posts

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
<center><strong><a href="index.php">Home</a></strong></center>
<?php

?>

</body>
</html>
<?php
    if (@$_GET['action']=='sign_out') {
    session_destroy();
    header('location: index.php');
    }
} else {header('location: index.php');}