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
<center>
<?php
//header
include('header.php');

//body
?>
</body>
</html>
<?php
} else {header('location: index.php');}