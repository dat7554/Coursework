<?php
//TODO: add share function
//TODO: add image
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
    echo "<strong><a href='index.php'>Home</a> | <a href='create_post.php'>Create a post</a></strong>";
    if ($_SESSION['user_roleID'] == 1) {
        echo "<strong> | <a href='create_module.php'>Create a module</a></strong>";
    }

    echo "<p>Welcome, <a style='text-decoration: none' href='profile.php?user_id={$_SESSION['userID']}'><b>" . @$_SESSION['email'] . "</b></a> | <a href='index.php?action=sign_out'>Sign out</a></p>";

} else { //header public view
    echo "<strong><a href='index.php'>Home</a> | <a href='sign_in.php'>Sign in</a> | <a href='sign_up.php'>Sign up</a></strong>";
}

//body
if ($_GET['id']) {
    $sql = "SELECT p.*, u.username AS create_username, u2.username AS update_username 
            FROM post p 
            LEFT JOIN user u ON p.userID = u.userID 
            LEFT JOIN user u2 ON p.update_userID = u2.userID 
            WHERE p.postID = :postID";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':postID', $_GET['id'], PDO::PARAM_STR);

    if ($statement->execute()) {
        //loop through each row of the result set
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            echo "<h1>" . htmlspecialchars($row['title']) . "</h1>";
            echo "<div>";
            echo "<table border='1px'>";

            //first row for content
            echo "<tr>";
            echo "<td width='900px' colspan='2'>" . htmlspecialchars($row['content']) . "</td>";
            echo "</tr>";

            //second row for share link, edit, creator and dates
            echo "<tr>";

            //if session, able to edit
            if (@$_SESSION['email'] && ($row['userID'] == $_SESSION['userID'] or $_SESSION['user_roleID'] == 1)) {
                echo "<td width='65%'><a>Share</a> <a href='edit_post.php?id={$row['postID']}'>Edit</a> <a href='delete_post.php?id={$row['postID']}'>Delete</a></td>";
            } else {
                echo "<td width='65%'><a>Share</a></td>";
            }

            echo "<td><table style='float: right' cellspacing='10'><tr>";
            echo "<tr>";

            if ($row['update_date']) {
                echo "<td>Updated " . htmlspecialchars($row['update_date']) . "</td>";
            } else {
                echo "<td></td>";
            }

            echo "<td>Asked " . htmlspecialchars($row['create_date']) . "</td></tr>";
            echo "<tr>";

            if ($row['update_date'] && $row['update_userID'] != null) {
                echo "<td>" . htmlspecialchars($row['update_username']) . "</td>";
            } else {
                echo "<td></td>";
            }

            echo "<td>" . htmlspecialchars($row['create_username']) . "</td></tr>";

            echo "</table>";
            echo "</div>";
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