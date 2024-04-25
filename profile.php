<?php

//TODO: add personal information from database + total posts
//TODO: profile display as user_id=$user_id

session_start();
include_once('connection.php');
include_once('common_function.php');
if (@$_SESSION['email']) {
?>
<html lang="en">
<head>
    <title><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Profile'; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php
//header
include('header.php');

//body
?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</body>
</html>
<?php
} else {header('location: index.php');}