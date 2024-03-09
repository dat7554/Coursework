<?php
//TODO: fix update_date

include_once('config.php');
include_once('../connection.php');

try {
    $module_sql = "CREATE TABLE IF NOT EXISTS module (
    moduleID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    create_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    update_date DATETIME)";

    $pdo->exec($module_sql); // Execute the SQL statement to create the table
    echo "Module table created successfully!";
} catch (PDOException $e) {
    echo "Error creating user table: " . $e->getMessage();
}