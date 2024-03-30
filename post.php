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
include('header.php');

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
            echo "<table>";

            //first row for content
            echo "<tr>";
            echo "<td style='border: 1px solid black; padding: 20px;' width='900px' colspan='2'>" . htmlspecialchars($row['content']);

            if (!empty($row['image'])) {
                echo "<br><img style='padding: 20px' alt='image' src='" . $row['image'] . "' width='50%'>";
            }
            echo "</td></tr>";

            //second row for share link, edit, creator and dates
            echo "<tr>";

            //if session, able to edit
            if (@$_SESSION['email'] && ($row['userID'] == $_SESSION['userID'] or $_SESSION['user_roleID'] == 1)) {
                echo "<td width='65%'><a>Share</a> <a href='edit_post.php?id={$row['postID']}'>Edit</a> <a href='delete_post.php?id={$row['postID']}'>Delete</a></td>";
            } else {
                echo "<td width='65%'><a>Share</a></td>";
            }

            echo "<td><table style='float: right' cellspacing='10' cellpadding='5'><tr>";
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
?>
</center>
</body>
</html>