<?php
//TODO: auto add admin, user, guest to new user_role table

include_once('config.php');
include_once('../connection.php');

try {
    $user_role_sql = "CREATE TABLE IF NOT EXISTS user_role (
    user_roleID INT AUTO_INCREMENT PRIMARY KEY,
    role VARCHAR(255) NOT NULL)";

    $pdo->exec($user_role_sql); // Execute the SQL statement to create the table
    echo "User role table created successfully!";
} catch (PDOException $e) {
    echo "Error creating user table: " . $e->getMessage();
}