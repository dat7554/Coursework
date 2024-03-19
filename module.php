<?php
//TODO: header
//TODO: dynamic title
//TODO: editable module

session_start();
include_once('connection.php');
include_once('common_function.php');
if (@$_SESSION['email']) {
?>
<html lang="en">
<head>
    <title><?php  ?></title>
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
