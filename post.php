<?php
//TODO: add update creator
//TODO: add create creator
//TODO: add share function
//TODO: add edit function for logged in user
//TODO: add comment section

session_start();
include_once('connection.php');
include_once('common_function.php');
?>

<html lang="en">
<head>
    <title>Post</title>
</head>
<body>
<center>
<?php
//header
if (@$_SESSION['email']) { //check session
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

    echo "<p>Welcome, <a style='text-decoration: none' href='profile.php?user_id={$user['userID']}'><b>" . @$_SESSION['email'] . "</b></a> | <a href='index.php?action=sign_out'>Sign out</a></p>";

} else { //header public view
    echo "<strong><a href='index.php'>Home</a> | <a href='sign_in.php'>Sign in</a> | <a href='sign_up.php'>Sign up</a></strong>";
}

//body
if ($_GET['id']) {
    $sql = "SELECT * FROM post WHERE postID = :postID";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':postID', $_GET['id'], PDO::PARAM_STR);

    if ($statement->execute()) {
        //loop through each row of the result set
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            echo "<h1>" . htmlspecialchars($row['title']) . "</h1>";
            echo "<table border='1px'>";

            //first row for content
            echo "<tr>";
            echo "<td width='900px' colspan='2'>" . htmlspecialchars($row['content']) . "</td>";
            echo "</tr>";

            //second row for share link, edit, creator and dates
            echo "<tr>";
            echo "<td><a>Share</a></td>";
            echo "<td><table border='1px'><tr>";
            if (empty($row['update_date'])) {
                //echo "<tr><td>Updated " . htmlspecialchars($row['update_date']) . "</td></tr>";
                echo "<td>Updated</td>";
            }
            echo "<td>Asked " . htmlspecialchars($row['create_date']) . "</td></tr>";
            echo "<tr><td></td>";
            echo "<td>" . htmlspecialchars($row['userID']) . "</td></tr>";
            echo "</table></td>";
            echo "</tr>";

            echo "</table>";
        }
    } else {
        echo "Error: not found ";
    }
} else {
    header('location: index.php');
}

include_once('sign_out.php');
?>
</center>
</body>
</html>