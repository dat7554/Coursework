<?php
//TODO: header
//TODO: dynamic title
//TODO: editable module
//TODO: make the creator column display the username
//TODO: htmlspecialchars

session_start();
include_once('connection.php');
include_once('common_function.php');
?>
<html lang="en">
<head>
    <title>
        <?php
        echo "Dynamic title"
        ?></title>
</head>
<body>
<center><strong><a href="index.php">Home</a></strong>

<?php
$sql = "SELECT p.*, u.username
        FROM post p 
        LEFT JOIN user u ON p.userID = u.userID 
        WHERE p.moduleID = :moduleID";
$statement = $pdo->prepare($sql);
$statement->bindParam(':moduleID', $_GET['id'], PDO::PARAM_STR);

//check if the query executed successfully
if ($statement->execute()) {
    //fetch and display the results in HTML table
    echo "<table border='1px'>
            <tr>
                <td width='500px' style='text-align: center'>Post</td>
                <td width='100px' style='text-align: center'>Views</td>
                <td width='100px' style='text-align: center'>Creator</td>
                <td width='100px' style='text-align: center'>Date created</td>
                <td width='100px' style='text-align: center'>Date updated</td>
            </tr>";

    //loop through each row of the result set
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td><a style='text-decoration: none' href='post.php?id={$row['postID']}'>{$row['title']}</a></td>
                <td>{$row['views']}</td>
                <td>{$row['username']}</td>
                <td>{$row['create_date']}</td>
                <td>{$row['update_date']}</td>
              </tr>";
    }
    echo "</table>"; //close the HTML table
} else {
    //handle the case where the query fails
    echo "Error: " . $sql . "<br>" . $statement->errorInfo()[2];
}
?>
</center>
</body>
</html>
<?php
if (@$_SESSION['email']) {
include_once('sign_out.php');
}