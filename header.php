<?php
if (isset($_SESSION['email'])) { //check session
    $email = $_SESSION['email'];
    $sql = "SELECT userID, user_roleID FROM user WHERE email = :email";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':email', $email, PDO::PARAM_STR);
    $statement->execute();
    $user = $statement->fetch();

    echo "<strong><a href='index.php'>Home</a> | <a href='create_post.php'>Create a post</a></strong>";

    if ($user['user_roleID'] == 1) {
        echo "<strong> | <a href='create_module.php'>Create a module</a></strong>";
    }

    echo "<p>Welcome, <a style='text-decoration: none' href='profile.php?user_id={$_SESSION['userID']}'><b>" . $_SESSION['email'] . "</b></a> | <a href='index.php?action=sign_out'>Sign out</a></p>";

} else { //header public view
    echo "<strong><a href='index.php'>Home</a> | <a href='sign_in.php'>Sign in</a> | <a href='sign_up.php'>Sign up</a></strong>";
}