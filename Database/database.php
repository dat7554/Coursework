<?php
include_once('config.php');
include_once('../connection.php');

//create module table
createModuleTable($pdo);

//create post table
createPostTable($pdo);

//create user table
createUserTable($pdo);

//create user role table
createUserRoleTable($pdo);

//create answer table
createAnswerTable($pdo);


//function to create module table
function createModuleTable($pdo)
{
    try {
        $module_sql = "CREATE TABLE IF NOT EXISTS module (
            moduleID INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            views INT,
            userID INT NOT NULL,
            create_date DATETIME DEFAULT CURRENT_TIMESTAMP,
            update_date DATETIME ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (userID) REFERENCES user(userID),
        )";

        //execute the SQL statement to create the table
        $pdo->exec($module_sql);
        echo "Module table created successfully!<br>";
    } catch (PDOException $e) {
        echo "Error creating module table: " . $e->getMessage();
    }
}

//function to create post table
function createPostTable($pdo)
{
    try {
        $post_sql = "CREATE TABLE IF NOT EXISTS post (
            postID INT AUTO_INCREMENT PRIMARY KEY,
            userID INT NOT NULL,
            update_userID INT,
            moduleID INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            content TEXT(1000) NOT NULL,
            image VARCHAR(255),
            create_date DATETIME DEFAULT CURRENT_TIMESTAMP,
            update_date DATETIME ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (userID) REFERENCES user(userID),
            FOREIGN KEY (moduleID) REFERENCES module(moduleID)
        )";

        //execute the SQL statement to create the table
        $pdo->exec($post_sql);
        echo "Post table created successfully!<br>";
    } catch (PDOException $e) {
        echo "Error creating user table: " . $e->getMessage();
    }
}

//function to create user table
function createUserTable($pdo)
{
    try {
        $user_sql = "CREATE TABLE IF NOT EXISTS user (
            userID INT AUTO_INCREMENT PRIMARY KEY,
            user_roleID INT NOT NULL,
            email VARCHAR(255) NOT NULL,
            username VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            personal_description VARCHAR(1000) NOT NULL,
            register_date DATETIME DEFAULT CURRENT_TIMESTAMP,
            update_date DATETIME ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_roleID) REFERENCES user_role(user_roleID)
        )";

        //execute the SQL statement to create the table
        $pdo->exec($user_sql);
        echo "User table created successfully!";

        //check if admin account exists
        $admin_exists = $pdo->query("SELECT userID FROM user WHERE user_roleID = 1 LIMIT 1")->rowCount() > 0;

        if (!$admin_exists) {
            //add admin user
            $admin_email = "ndat7554@gmail.com";
            $admin_username = "admin";
            $admin_password = password_hash("123456789", PASSWORD_DEFAULT);

            $admin_insert_sql = "INSERT INTO user (user_roleID, email, username, password, personal_description) VALUES (1, :email, :username, :password, 'Admin')";
            $admin_statement = $pdo->prepare($admin_insert_sql);
            $admin_statement->execute([
                ':email' => $admin_email,
                ':username' => $admin_username,
                ':password' => $admin_password
            ]);

            echo " Admin account created successfully.<br>";
        } else {
            echo " Admin account already exists.<br>";
        }
    } catch (PDOException $e) {
        echo "Error creating user table: " . $e->getMessage();
    }
}

//function to create user role table
function createUserRoleTable($pdo)
{
    try {
        $user_role_sql = "CREATE TABLE IF NOT EXISTS user_role (
            user_roleID INT AUTO_INCREMENT PRIMARY KEY,
            role VARCHAR(255) NOT NULL
        )";

        //execute the SQL statement to create the table
        $pdo->exec($user_role_sql);
        echo "User role table created successfully!<br>";

        //retrieve existing roles from the database
        $existing_roles = [];
        $statement = $pdo->query("SELECT role FROM user_role");
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $existing_roles[] = $row['role'];
        }

        //new roles to be inserted
        $new_roles = ['admin', 'member'];

        //filter out new roles that do not already exist
        $roles_to_add = array_diff($new_roles, $existing_roles);

        if (!empty($roles_to_add)) {
            //prepare and execute the INSERT statement for new roles
            $placeholders = implode(',', array_fill(0, count($roles_to_add), '(?)'));
            $sql = "INSERT INTO user_role (role) VALUES $placeholders";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($roles_to_add);

            echo "New roles added successfully!<br>";
        }
    } catch (PDOException $e) {
        echo "Error creating user table: " . $e->getMessage();
    }
}

function createAnswerTable($pdo)
{
    try {
        $answer_sql = "CREATE TABLE IF NOT EXISTS answer (
            answerID INT AUTO_INCREMENT PRIMARY KEY,
            postID INT NOT NULL,
            userID INT NOT NULL,
            update_userID INT,
            content TEXT NOT NULL,
            create_date DATETIME DEFAULT CURRENT_TIMESTAMP,
            update_date DATETIME ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (postID) REFERENCES post(postID),
            FOREIGN KEY (userID) REFERENCES user(userID)
        );";

        //execute the SQL statement to create the table
        $pdo->exec($answer_sql);
        echo "Answer table created successfully!<br>";
    } catch (PDOException $e) {
        echo "Error creating user table: " . $e->getMessage();
    }
}