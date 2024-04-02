<?php
if (isset($_SESSION['email'])) { //check session
    echo "<strong><a href='index.php'>Home</a> | <a href='create_post.php'>Create a post</a></strong>";

    if ($_SESSION['user_roleID'] == 1) {
        echo "<strong> | <a href='create_module.php'>Create a module</a> | <a href='accounts.php'>Accounts list</a></strong>";
    }

    echo "<p>Welcome, <a style='text-decoration: none' href='profile.php?user_id={$_SESSION['userID']}'><b>" . $_SESSION['email'] . "</b></a> | <a href='index.php?action=sign_out'>Sign out</a></p>";

} else { //header public view
    echo "<strong><a href='index.php'>Home</a> | <a href='sign_in.php'>Sign in</a> | <a href='sign_up.php'>Sign up</a></strong>";
}