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

//create comment table
createCommentTable($pdo);

//function to create module table
//TODO: fix update_date
//TODO: check creator, consider to change to userID fk or not
function createModuleTable($pdo)
{
    try {
        $module_sql = "CREATE TABLE IF NOT EXISTS module (
            moduleID INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            views INT,
            creator VARCHAR(255) NOT NULL,
            create_date DATETIME DEFAULT CURRENT_TIMESTAMP,
            update_date DATETIME ON UPDATE CURRENT_TIMESTAMP
        )";

        //execute the SQL statement to create the table
        $pdo->exec($module_sql);
        echo "Module table created successfully!<br>";
    } catch (PDOException $e) {
        echo "Error creating module table: " . $e->getMessage();
    }
}

//function to create post table
//TODO: fix update_date
//TODO: add update creator ID
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
            image VARCHAR(255) NOT NULL,
            views INT,
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
//TODO: user_roleID currently fk in user, should consider user_roleID out of user (userID fk of user_role) or not ? later is easy to add new permission
//TODO: auto add admin user to the db with roleID = 1
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
            active BOOLEAN DEFAULT true,
            log_in DATETIME NOT NULL,
            register_date DATETIME DEFAULT CURRENT_TIMESTAMP,
            update_date DATETIME ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_roleID) REFERENCES user_role(user_roleID)
        )";

        //execute the SQL statement to create the table
        $pdo->exec($user_sql);
        echo "User table created successfully!<br>";

        //add admin user
        $admin_email = "ndat7554@gmail.com";
        $admin_username = "admin";
        $admin_password = password_hash("123456789", PASSWORD_DEFAULT);

        $admin_insert_sql = "INSERT INTO user (user_roleID, email, username, password, personal_description) VALUES (1, :email, :username, :password, 'Admin user')";

        $admin_statement = $pdo->prepare($admin_insert_sql);
        $admin_statement->bindParam(':email', $admin_email, PDO::PARAM_STR);
        $admin_statement->bindParam(':username', $admin_username, PDO::PARAM_STR);
        $admin_statement->bindParam(':password', $admin_password, PDO::PARAM_STR);
        $admin_statement->execute();
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
        $new_roles = ['admin', 'member', 'guest'];

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

function createCommentTable($pdo)
{
    try {
        $comment_sql = "CREATE TABLE IF NOT EXISTS comment (
            commentID INT AUTO_INCREMENT PRIMARY KEY,
            answerID INT NOT NULL,
            userID INT NOT NULL,
            content TEXT NOT NULL,
            create_date DATETIME DEFAULT CURRENT_TIMESTAMP,
            update_date DATETIME ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (answerID) REFERENCES answer(answerID),
            FOREIGN KEY (userID) REFERENCES user(userID)
        );";

        //execute the SQL statement to create the table
        $pdo->exec($comment_sql);
        echo "Comment table created successfully!<br>";
    } catch (PDOException $e) {
        echo "Error creating user table: " . $e->getMessage();
    }
}