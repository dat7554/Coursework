<?php
//TODO: fix update_date

include_once('config.php');
include_once('../connection.php');

try {
    $post_sql = "CREATE TABLE IF NOT EXISTS post (
    postID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    moduleID INT NOT NULL,
    image VARCHAR(255) NOT NULL,
    create_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    update_date DATETIME,
    FOREIGN KEY (userID) REFERENCES user(userID),
    FOREIGN KEY (moduleID) REFERENCES module(moduleID))";

    $pdo->exec($post_sql); // Execute the SQL statement to create the table
    echo "Post table created successfully!";
} catch (PDOException $e) {
    echo "Error creating user table: " . $e->getMessage();
}