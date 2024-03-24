<?php
//TODO: add personal information from database + add/change profile pic function + change pass + total posts
//TODO: header
//TODO: profile display as user_id=$user_id
//TODO: htmlspecialchars

session_start();
include_once('connection.php');
include_once('common_function.php');
if (@$_SESSION['email']) {
?>
<html lang="en">
<head>
    <title><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Profile'; ?></title>
</head>
<body>
<center><strong><a href="index.php">Home</a></strong></center>
<?php

?>

</body>
</html>
<?php
include_once('sign_out.php');
} else {header('location: index.php');}