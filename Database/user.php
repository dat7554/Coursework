<?php
//user_roleID currently fk in user, should consider user_roleID out of user (userID fk of user_role) or not ?
//later is easy to add new permission

include_once('config.php');
include_once('../connection.php');

try {
    $user_sql = "CREATE TABLE IF NOT EXISTS user (
    userID INT AUTO_INCREMENT PRIMARY KEY,
    user_roleID INT NOT NULL,
    email VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    personal_description VARCHAR(1000) NOT NULL,
    active BOOLEAN DEFAULT true,
    log_in DATETIME NOT NULL,
    register_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_roleID) REFERENCES user_role(user_roleID))";

    $pdo->exec($user_sql); // Execute the SQL statement to create the table
    echo "User table created successfully!";
} catch (PDOException $e) {
    echo "Error creating user table: " . $e->getMessage();
}