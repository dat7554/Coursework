<?php
session_start();
include_once('connection.php');
include_once('common_function.php');
?>

<html lang="en">
<head>
    <title>Forum</title>
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
$sql = "SELECT name, views, creator, create_date, update_date FROM module";
$statement = $pdo->prepare($sql);

//check if the query executed successfully
if ($statement->execute()) {
    //fetch and display the results in HTML table
    echo "<table border='1px'>
            <tr>
                <td width='500px' style='text-align: center'>Module</td>
                <td width='100px' style='text-align: center'>Views</td>
                <td width='100px' style='text-align: center'>Creator</td>
                <td width='100px' style='text-align: center'>Date created</td>
                <td width='100px' style='text-align: center'>Date updated</td>
            </tr>";

    //loop through each row of the result set
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td>{$row['name']}</td>
                <td>{$row['views']}</td>
                <td>{$row['creator']}</td>
                <td>{$row['create_date']}</td>
                <td>{$row['update_date']}</td>
              </tr>";
    }
    echo "</table>"; //close the HTML table
} else {
    //handle the case where the query fails
    echo "Error: " . $sql . "<br>" . $statement->errorInfo()[2];
}

include_once('sign_out.php');
?>
</center>
</body>
</html>