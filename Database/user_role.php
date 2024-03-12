<?php
//TODO: check again auto add admin, user, guest to new user_role table

include_once('config.php');
include_once('../connection.php');

try {
    $user_role_sql = "CREATE TABLE IF NOT EXISTS user_role (
    user_roleID INT AUTO_INCREMENT PRIMARY KEY,
    role VARCHAR(255) NOT NULL)";

    $pdo->exec($user_role_sql); //execute the SQL statement to create the table
    echo "User role table created successfully!";

    //retrieve existing roles from the database
    $existingRoles = [];
    $stmt = $pdo->query("SELECT role FROM user_role");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $existingRoles[] = $row['role'];
    }

    //new roles to be inserted
    $newRoles = ['admin', 'member', 'guest'];

    //filter out new roles that do not already exist
    $rolesToAdd = array_diff($newRoles, $existingRoles);

    if (!empty($rolesToAdd)) {
        //prepare and execute the INSERT statement for new roles
        $placeholders = implode(',', array_fill(0, count($rolesToAdd), '(?)'));
        $sql = "INSERT INTO user_role (role) VALUES $placeholders";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($rolesToAdd);

        echo "New roles added successfully!";
    }
} catch (PDOException $e) {
    echo "Error creating user table: " . $e->getMessage();
}