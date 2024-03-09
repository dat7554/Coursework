<?php
//reference: https://www.phptutorial.net/php-pdo

include_once('database/config.php');

$dsn = "mysql:host=$host;dbname=$db;charset=UTF8";

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected to the $db database successfully!";
} catch (PDOException $e) {
    echo $e->getMessage();
}